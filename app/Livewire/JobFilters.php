<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JobPosting;
class JobFilters extends Component
{
    public $selectedCategory = 'all';

    public function render()
    {
        $jobs = JobPosting::when($this->selectedCategory !== 'all', function ($query) {
            $query->where('category', $this->selectedCategory);
        })->get();

        return view('livewire.job-filters', compact('jobs'));
    }
}
