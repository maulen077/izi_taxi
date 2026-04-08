@extends('admin.layouts.admin')

@section('content')
    <div class="container">
        <h1>Редактировать цену на топливо</h1>

        <form method="POST" action="{{ route('update', $oil->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="price">Цена:</label>
                <input type="text" name="price" id="price" class="form-control" value="{{ $oil->price }}">
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a href="{{ route('prices') }}" class="btn btn-secondary">Отмена</a>
            </div>

        </form>
    </div>
@endsection
