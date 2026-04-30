<?php

namespace App\Livewire\Components\Modals;

use Livewire\Component;
use App\Models\Vehicle;
use Livewire\Attributes\On;
use App\Models\Brand;
use App\Models\Models;

class EditVehicleModal extends Component
{
    public bool $show = false;
    public $marca;
    public $modelo;
    public $vehicleId;
    public $marca_id = null; 
    public $modelo_id = null;
    public $resultadosMarca = [];
    public $resultadosModelo = [];
    public $placa;
    
    protected function rules(): array
    {
        return [
            'placa' => 'required|min:6|unique:vehicles,placa,'.$this->vehicleId,
            'modelo' => 'required',
            'marca' => 'required',
        ];
    }

    protected function messages(): array
    {
         return [
            'placa.required' => 'La placa es obligatoria',
            'placa.min' => 'La placa debe tener al menos 6 caracteres',
            'placa.unique' => 'Este numero de placa ya se encuentra registrado',

            'marca.required' => 'La marca es obligatoria',
            'modelo.required' => 'El modelo es obligatorio',
        ];
    }

    public function buscar($tipo)
    {
        if ($tipo === 'marca') {
            if (strlen($this->marca) < 1) {
                $this->resultadosMarca = [];
                return;
            }

            $this->resultadosMarca = Brand::where('name', 'LIKE', $this->marca . '%')
                ->limit(5)
                ->pluck('name', 'id')
                ->toArray();
        }
        if ($tipo === 'modelo') {
            if (strlen($this->modelo) < 1) {
                $this->resultadosModelo = [];
                return;
            }

            $this->resultadosModelo = Models::where('name', 'LIKE', $this->modelo . '%')
                ->limit(5)
                ->pluck('name', 'id')
                ->toArray();
        }
    }
     public function seleccionar($tipo,$id,$valor)
    {
            if ($tipo === 'marca') {
                $this->marca = $valor;
                $this->marca_id = $id;    

                $this->modelo = '';
                $this->modelo_id = null;

                $this->resultadosMarca = [];
            }

            if ($tipo === 'modelo') {
                $this->modelo = $valor;
                $this->modelo_id = $id;

                $this->resultadosModelo = [];
            }
    }
    #[On('openEditModal')]
    public function openEditModal($id)
    {         
        $vehicle = Vehicle::find($id);
        $this->placa = $vehicle->placa;
        $this->vehicleId = $vehicle->id;
        $this->show   = true;
    }
    public function update()
    {
        $this->validate();

        Vehicle::where('id', $this->vehicleId)
        ->update([               
            'placa'  => strtoupper($this->placa),
            'model_id' => strtoupper($this->modelo_id ),
        ]);

        $this->show = false;
        $this->dispatch('tableRefresh');

        $this->dispatch('swal', [
            'icon'  => 'success',
            'title' => '¡Actualizado!',
            'showConfirmButton' => true,
        ]);
    }
    public function render()
    {
        return view('livewire.components.modals.edit-vehicle-modal');
    }
}
