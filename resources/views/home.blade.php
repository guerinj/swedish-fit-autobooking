@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center">

                        <h1>Swedish Fit auto-login</h1>
                        <p>Réservez vos cours Swedish Fit en avance.</p>

                        <div class="card-body text-center">

                            <h3 class>Réservations à venir</h3>
                            <small class="text-muted">Vous n'avez aucune réservation à venir.</small>
                        </div>
                        <div class="card-body text-center">

                            <h3>Créer une réservation</h3>
                            <div class="form-group row">
                                <div class="col-8">


                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text"
                                              id="sf_id">
                                            https://www.swedishfit.fr/cours/detail/?id=
                                        </span>
                                        </div>
                                        <input type="text" class="form-control" id="sf_id"
                                               aria-describedby="sf_id">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <button class="btn btn-primary btn-block">Rechercher</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection
