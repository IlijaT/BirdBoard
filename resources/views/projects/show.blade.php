@extends('layouts.app')
@section('content')

    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between items-end w-full">
            <p class="text-lg text-grey text-sm font-normal">
               <a href="/projects" 
                class="text-lg text-grey text-sm font-normal"
                style="text-decoration: none;"
                >
                My Projects
                </a>  / {{ $project->title }}
            </p>
            <a class="text-lg button text-white" style="text-decoration: none;" href="{{ $project->path() }}/edit">Update Project</a>
        </div>
    </header>

    <div class="lg:flex -mx-3">
        
        <div class="lg:w-3/4 px-3 mb-6">
            
            <div class="mb-10">
                <h2 class="text-lg text-grey font-normal mb-3">Tasks</h2>


                @foreach($project->tasks as $task)
                    <div class="bg-white p-3 mb-3 card w-full">
                        <form method="POST" action="{{ $task->path() }}">
                            @method('PATCH')
                            @csrf
                            <div class="flex">
                                <input class="w-full {{ $task->completed ? 'text-grey' : ''}}" name="body" type="text" value="{{ $task->body }}">
                                <input type="checkbox" name="completed" onChange="this.form.submit()" {{ $task->completed ? 'checked' : ''}}>
                            </div>
                        </form>
                    </div>
                @endforeach

                <div class="bg-white p-3 mb-3 card w-full">
                    <form action="{{ $project->path().'/tasks'}}" method="POST">
                        @csrf
                        <input name="body" class="w-full" type="text" placeholder="Add a new task...">
                    </form>
                </div>

            </div>
            
            <div>
                <h2 class="text-lg text-grey font-normal mb-3">General Notes</h2>
                
                <!-- General notes -->

                <form action="{{ $project->path() }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <textarea 
                        name="notes"
                        class="bg-white p-4 card w-full mb-4" 
                        style="min-height: 200px"
                        placeholder="Anything special that you want to make a note of?"
                    >{{ $project->notes }}</textarea>
                    <button type="submit" class="text-lg button text-white">Save</button>
                </form>
            </div>

        </div>
        
        <div class="lg:w-1/4 px-3">
            <div class="bg-white p-5 card" >
                <h3 class="font-normal mb-3 text-xl py-2 -ml-12 border-l-4 border-blue-light pl-4">
                    <a style="text-decoration: none" class="text-black" href="{{ $project->path() }}">{{ $project->title }} </a>
                </h3>
                <div class="text-grey">{{ str_limit($project->description, 100) }}</div>
            </div>
        </div>

    </div>
@endsection 