var app = new Vue({
  el: '#app',
  data: {
  	title: "Vehicle API",
  	vehicles: [],
  	numVehicles: 0,
  	makerModels: [],
  	selectedModel: 0,
  	selectedMake: 0
  },
  created: function() {
  	var comp = this;
  	axios.get('api').then(function(response) {
  		if (response.data) {
  			var data = response.data;
  			if (data.valid) {
  				comp.numVehicles = data.numVehicles;
  				if (comp.numVehicles > 0) {
  					comp.assignVehicles(data);  					
  				}
  			}
  		}
  	})
  	.catch(function(error) {
  		console.log(error)
  	});
  },
  computed: {
  	hasVehicles: function() {
  		return this.numVehicles > 0;
  	},
  	numMakers: function() {
  		return this.makerModels.length;
  	}
  },
  filters: {
	  yesNo: function (value) {
	    var valid = value === true || value > 0;
	    return valid? "Yes" : "No";
	  }
	},
	methods: {
		assignVehicles: function(data) {
			var i = 0,
				vehicle, 
				model,
				maker,
				makerIndex,
				modelIndex;
			for (; i < data.numVehicles; i++) {
				vehicle = data.vehicles[i];
				model = vehicle.model;
				maker = vehicle.model.maker;
				makerIndex = this.makerModels.findIndex(function(m) {
					return m.id == maker.id;
				});
				vehicle.filterClasses = [
					'model-' + model.id,
					'maker-' + maker.id
				];
				vehicle.showClass = 'show';
				if (makerIndex < 0) {
					maker.models = [model];
					this.makerModels.push(maker);
				} else {
					modelIndex = this.makerModels[makerIndex].models.findIndex(function(m) {
						return m.id == model.id;
					});
					if (modelIndex < 0) {
						this.makerModels[makerIndex].models.push(model);
					}
				}
			}
			this.makerModels = this.makerModels.sort(function(a,b) {
				return a.name.toLowerCase() < b.name.toLowerCase()? -1 : 1;
			});
			this.vehicles = data.vehicles;
		},
		filterByMaker: function(makerId) {
			this.selectedMake = makerId;
			this.selectedModel = 0;
			this.filterBy(makerId, 'maker');
		},
		filterByModel: function(modelId) {
			this.selectedMake = 0;
			this.selectedModel = modelId;
			this.filterBy(modelId, 'model');
		},
		filterBy: function(ref,field) {
			var i = 0, show = false, vehicle;
			for (; i < this.numVehicles; i++) {
				vehicle = this.vehicles[i];
				switch (field) {
					case 'maker':
						show = vehicle.model.maker.id === ref;
						break;
					case 'model':
						show = vehicle.model.id === ref;
						break;
					default:
						show = true;
						break;
				}
				vehicle.showClass = show? 'show' : 'hide';
			}
		},
		resetFilter: function() {
			this.selectedMake = 0;
			this.selectedModel = 0;
			this.filterBy(0, 'reset');
		}
	}
});