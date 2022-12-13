<?php

namespace App\Http\Livewire;

use App\Models\File;
use Livewire\Component;
use Livewire\WithFileUploads;

class Uploader extends Component
{
    use WithFileUploads;

    public $files = [];

    protected $rules = [
        'files' => ['required', 'file', 'max:2000', 'mimes:pdf,doc,docx,txt,rtf,odt,jpg,jpeg,png,gif,svg,zip,rar']
    ];

    protected $messages = [
        'files.required' => 'Please select a file to upload.',
        'files.file' => 'The file must be a file.',
        'files.max' => 'The file may not be greater than 2MB.',
        'files.mimes' => 'The file must be a file of type: pdf, doc, docx, txt, rtf, odt, jpg, jpeg, png, gif, svg, zip, rar.'
    ];


    public function updatedFiles()
    {
        $this->validate();

        collect($this->files)->each(function ($file) {
            File::create([
                'file_name' => $name = $file->getClientOriginalName(),
                'file_path' => $file->storeAs('files', $name)
            ]);
        });

        $this->upload();
        $this->emitTo('uploaded-files', 'refresh');
    }



    public function upload()
    {
        collect($this->files)->each(function ($file) {
            File::create([
                'file_name' => $name = $file->getClientOriginalName(),
                'file_path' => $file->storeAs('files', $name)
            ]);
        });
    }

    public function render()
    {
        return view('livewire.uploader');
    }
}
