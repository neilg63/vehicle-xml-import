<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Vehicle API</title>
        <script src="https://cdn.jsdelivr.net/npm/vue@2.5.13/dist/vue.js"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">
        <link rel="stylesheet" href="/css/app.css" >
    </head>
    <body>
        <div id="app">
            <h1>{{title}}</h1>
            <aside class="sidebar">
                <h3 v-on:click="resetFilter()" class="reset actionable">Show all</h3>
                <ul v-if="numMakers > 0" class="pure-menu-list makers-list">
                    <li v-for="maker in makerModels" class="pure-menu-item">
                        <strong class="maker-name actionable" :class="{'selected': maker.id == selectedMake}" v-on:click.stop="filterByMaker(maker.id)">{{maker.name}}</strong>
                        <ul v-if="maker.models.length>0" class="pure-menu-list">
                            <li v-for="model in maker.models" class="pure-menu-item actionable" :class="{'selected': model.id == selectedModel}" v-on:click.prevent="filterByModel(model.id)">{{model.name}}</li>
                        </ul>
                    </li>
                </ul>
            </aside>
            <section v-if="numVehicles > 0" class="main">
                <article v-for="(vehicle,index) in vehicles" v-bind:class="vehicle.showClass">
                    <h3>
                        <span class="maker-name">{{vehicle.model.maker.name}}</span>
                        <span class="model-name">{{vehicle.model.name}}</span>
                    </h3>
                    <dl>
                        <dt>Licence plat</dt>
                        <dd>{{vehicle.reg}}</dd>
                        <dt>Engine CC</dt>
                        <dd>{{vehicle.engine_cc}}</dd>
                        <dt>No. of doors</dt>
                        <dd>{{vehicle.no_doors}}</dd>
                        <dt>Colour</dt>
                        <dd>{{vehicle.colour}}</dd>
                        <dt>Usage</dt>
                        <dd>{{vehicle.usage}}</dd>
                        <dt>No. of wheels</dt>
                        <dd>{{vehicle.model.no_wheels}}</dd>
                        <dt>GPS</dt>
                        <dd>{{vehicle.has_gps|yesNo}}</dd>
                        <dt>Boot</dt>
                        <dd>{{vehicle.has_boot|yesNo}}</dd>
                        <dt>Sunroof</dt>
                        <dd>{{vehicle.has_sunroof|yesNo}}</dd>
                        <dt>Trailer</dt>
                        <dd>{{vehicle.has_trailer|yesNo}}</dd>
                    </dl>
                    <dl v-for="owner in vehicle.owners">
                        <dt>Owner name</dt>
                        <dd>{{owner.name}}</dd>
                        <dt>Profession</dt>
                        <dd>{{owner.profession}}</dd>
                        <dt v-if="owner.company">Company name</dt>
                        <dd v-if="owner.company">{{owner.company.name}}</dd>
                    </dl>
                </article>
                </article>
            </div>

        </div>
        <script src="/js/front.js"></script>
    </body>
</html>
