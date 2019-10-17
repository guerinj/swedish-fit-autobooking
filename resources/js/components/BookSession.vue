<template>
    <div>
        <h3>Ajouter une réservation automatique</h3>
        <div v-if="sessionDetails" class="row">
            <div class="col-5 text-right">Date</div>
            <div class="col-7 text-left">{{sessionDetails.date}}</div>
            <div class="col-5 text-right">Heure</div>
            <div class="col-7 text-left">{{sessionDetails.time}}</div>
            <div class="col-5 text-right">Lieu</div>
            <div class="col-7 text-left">{{sessionDetails.location}}</div>
            <div class="col-5 text-right">Type</div>
            <div class="col-7 text-left">{{sessionDetails.type}}</div>

            <div class="mt-3 col-12">
                <form action="/bookings" method="post">
                    <input type="hidden" name="_token" :value="csrfToken">
                    <input type="hidden" name="swedishfit_id" :value="sessionId">
                    <input type="hidden" name="details" :value="serializedSessionDetails">
                    <button class="btn btn-primary">Réserver ce cours</button>
                </form>
            </div>

        </div>
        <div v-else class="form-group row">
            <div class="col-md-8">


                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                                        <span class="input-group-text"
                                              id="sf_id">
                                            https://www.swedishfit.fr/cours/detail/?id=
                                        </span>
                    </div>
                    <input type="text" class="form-control"
                           v-model="sessionId"
                           aria-describedby="sf_id">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-control-plaintext" v-if="isLoading"> Recherche en cours...</div>
                <button v-else class="btn btn-primary btn-block" @click="getDetails">Rechercher</button>
            </div>
            <div v-if="hasError" class="col-12 text-center">
                <small class="text-danger">Erreur de lors de la récupération du cours</small>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                csrfToken: window.Laravel,
                sessionId: null,
                sessionDetails: null,
                isLoading: false,
                hasError: false,
            }
        },
        methods: {
            getDetails() {
                this.isLoading = true;
                this.hasError = false;
                this.axios
                    .get('/session/' + this.sessionId + '/details')
                    .then((response) => {
                        if (response.status !== 200) {
                            throw new Error('Could not retrieve session details')
                        }
                        this.sessionDetails = response.data.data;
                    })
                    .catch((e) => {
                        console.log(e);
                        this.sessionDetails = null;
                        this.hasError = true;
                    })
                    .finally(() => this.isLoading = false);

            }
        },
        computed: {
            serializedSessionDetails() {
                return JSON.stringify(this.sessionDetails);
            }
        }
    }
</script>
