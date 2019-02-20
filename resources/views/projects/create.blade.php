@extends('layouts.app')
@section('content')
    <div class="lg:w-1/2 lg:mx-auto bg-white p-6 md:py-12 md:px-16 rounded shadow">
        <h1 class="text-2xl font-normal mb-10 text-center">
            Create an Announcement
        </h1>

        <form action="/projects" method="POST">
            @csrf
            
            <div class="field mb-6">
                <label class="label text-sm mb-2 block" for="title">Title</label>

                <div class="control">
                    <input
                        type="text"
                        class="input bg-transparent border border-grey-light rounded p-2 text-xs w-full"
                        name="title"
                        placeholder="Project title here..."
                        required
                        >
                </div>
            </div>

            <div class="field mb-6">
                <label class="label text-sm mb-2 block" for="description">Description</label>

                <div class="control">
                        <textarea
                            name="description"
                            rows="10"
                            class="textarea bg-transparent border border-grey-light rounded p-2 text-xs w-full"
                            placeholder="Project description here..."
                            required></textarea>
                </div>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <button class="text-lg button text-white is-link mr-2">Create</button>
                    <a href="/projects">Cancel</a>
                </div>
            </div>
        </form>
    </div>

       
 
@endsection 
 