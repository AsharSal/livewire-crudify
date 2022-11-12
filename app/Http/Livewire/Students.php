<?php

namespace App\Http\Livewire;
use Livewire\Component;
use App\Models\Student;
class Users extends Component
{
    public $users, $name, $email, $user_id;
    public $updateMode = false;

    public function render()
    {
        $this->users = Student::all();
        return view('livewire.students');
    }

    private function resetInputFields(){
        $this->name = '';
        $this->email = '';
    }
    public function store()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);
        Student::create($validatedDate);

        session()->flash('message', 'Student Created Successfully.');

        $this->resetInputFields();

        $this->emit('studentStore'); // Close model to using to jquery
    }
    public function edit($id)
    {
        $this->updateMode = true;
        $user = Student::where('id',$id)->first();
        $this->user_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
    }
    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();
    }
    public function update()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);
        if ($this->user_id) {
            $user = Student::find($this->user_id);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);
            $this->updateMode = false;
            session()->flash('message', 'Student Updated Successfully.');
            $this->resetInputFields();
        }
    }
    public function delete($id)
    {
        if($id){
            Student::where('id',$id)->delete();
            session()->flash('message', 'Student Deleted Successfully.');
        }
    }
}
