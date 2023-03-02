

<template>
  <div v-if="isDisplay">
      <!-- The backdrop -->
      <div class="fixed inset-0 bg-gray-900 opacity-40"></div>

      <!-- Where the actual content goes -->
      <div class="fixed inset-0 flex items-center justify-center">
        <div class="p-4 bg-white text-black w-4/5 sm:w-4/5 md:w-3/5 lg:w-2/6">

            <div class="text-lg text-center mb-4">Weather</div>

            <hr />

            <div class="text-md p-4">
      
              <template v-if="!weather">
                Pinging the api...
              </template>

              <template v-else>
                <p class="flex flex-col items-center justify-center">
                  <img :src="icon" alt="Weather icon">
                  <strong>{{weather.weather[0]?.main}}</strong>
                </p>
                <p class="mt-3">
                  <strong>Temperature</strong>: {{weather.main.temp}}℃
                </p>
                <p>
                  <strong>Wind Speed:</strong> {{weather.wind.speed}}km/h
                </p>
              </template>

            </div>

            <hr />

            <div class="row text-right mt-4">
              <button
                type="button"
                class="bg-indigo-200 px-3 py-1 font-medium"
                @click="isDisplay = false"
              >
                Close
              </button>   
            </div>
        </div>
      </div>
    </div>
</template>

<script>
export default {
  data: () => ({
    user: null,
    weather: null,
    isDisplay: false
  }),

  computed: {
    icon() {
      return `http://openweathermap.org/img/w/${this.weather.weather[0]?.icon}.png`;
    }
  },

  methods: {
    show: async function(user) {
      this.user = user;
      this.isDisplay = true;
      this.weather = null;

      const url = `http://localhost/api/v1/weather/${user.latitude}/${user.longitude}`;

      let response = await (await fetch(url)).json();

      if (response?.status === 'success') {
        this.weather = response.data;
      }
      
    }
  }
}
</script>