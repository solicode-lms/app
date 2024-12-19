{{-- Ce fichier est maintenu par ESSARRAJ Fouad --}}

@extends('layouts.admin')
@section('title', __('Core::msg.show') . ' ' . __('PkgBlog::comment.singular'))
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Core::msg.detail') }}</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('comments.edit', $item->id) }}" class="btn btn-default float-right">
                        <i class="far fa-edit"></i>
                        {{ __('Core::msg.edit') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-sm-12">
                                <label for="content">{{ ucfirst(__('PkgBlog::comment.content')) }}:</label>
                                <p>{{ $item->content }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="user_id">{{ ucfirst(__('PkgBlog::comment.user_id')) }}:</label>
                                <p>{{ $item->user_id }}</p>
                            </div>
                            <div class="col-sm-12">
                                <label for="article_id">{{ ucfirst(__('PkgBlog::comment.article_id')) }}:</label>
                                <p>{{ $item->article_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
