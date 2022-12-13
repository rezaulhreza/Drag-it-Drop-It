<?php

namespace Tests\Feature\Livewire;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Uploader;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UploaderTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(Uploader::class);

        $component->assertStatus(200);
    }

    /** @test */
    function the_component_can_upload_files()
    {
        Livewire::test(Uploader::class)
            ->set('files', [$file = UploadedFile::fake()->create('file.pdf', 1000)])
            ->call('upload', $file)
            ->assertOk();

        $this->assertDatabaseHas('files', [
            'file_name' => 'file.pdf',
            'file_path' => 'files/file.pdf'
        ]);
    }

    /** @test */
    function the_component_can_validate_files()
    {
        Livewire::test(Uploader::class)
            ->set('files', [$file = UploadedFile::fake()->create('file.pdf', 3000)])
            ->call('upload', $file)
            ->assertHasErrors();
    }

    /** @test */
    function the_component_can_refresh_files()
    {
        Livewire::test(Uploader::class)
            ->set('files', [$file = UploadedFile::fake()->create('file.pdf', 1000)])
            ->call('upload', $file)
            ->call('$refresh')
            ->assertStatus(200);
    }

    /** @test */
    function the_component_can_upload_multiple_files()
    {
        Livewire::test(Uploader::class)
            ->set('files', [
                $file1 = UploadedFile::fake()->create('file1.pdf', 1000),
                $file2 = UploadedFile::fake()->create('file2.pdf', 1000),
                $file3 = UploadedFile::fake()->create('file3.pdf', 1000),
            ])
            ->call('upload', $file1)
            ->call('upload', $file2)
            ->call('upload', $file3)
            ->assertOk();

        $this->assertDatabaseHas('files', [
            'file_name' => 'file1.pdf',
            'file_path' => 'files/file1.pdf'
        ]);

        $this->assertDatabaseHas('files', [
            'file_name' => 'file2.pdf',
            'file_path' => 'files/file2.pdf'
        ]);

        $this->assertDatabaseHas('files', [
            'file_name' => 'file3.pdf',
            'file_path' => 'files/file3.pdf'
        ]);
    }
}
