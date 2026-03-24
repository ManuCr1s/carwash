<?php

namespace App\Livewire\Components\Modals;

use Livewire\Component;
use Livewire\Attributes\On;

class MainModal extends Component
{
    public $type = 'confirm';
    public $show = false;
    public $title = '';
    public $message = '';
    public $method = null;
    public $params = null;

    protected $listeners = ['open-modal' => 'open'];

    public function open($data)
    {
        $this->title = $data['title'] ?? '';
        $this->message = $data['message'] ?? '';
        $this->type = $data['type'] ?? 'confirm';
        $this->method = $data['method'] ?? null;
        $this->params = $data['params'] ?? null;
        $this->show = true;
    }

    public function close()
    {
        $this->reset(['show', 'title', 'message', 'method', 'params']);
    }

    public function confirm()
    {
        if ($this->method) {
            $this->dispatch($this->method, $this->params);
        }

        $this->close();
    }

    public function render()
    {
        return view('livewire.components.modals.main-modal');
    }
}
