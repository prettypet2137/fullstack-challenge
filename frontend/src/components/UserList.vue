

<template>
  <div class="p-4">
    <h2 class="text-lg font-bold mb-2">User List</h2>

    <div v-if="!users">
      Pinging the api...
    </div>

    <div v-else>    
      <table class="hover:table-fixed">
        <thead>
          <tr>
            <th class="p-4">Name</th>
            <th class="p-4">Email</th>
            <th class="p-4">Location</th>
          </tr>
        </thead>
        <tbody>
          <tr
            class="cursor-pointer hover:bg-slate-300" 
            v-for="user in users"
            :key="user.id"
            @click="showWeatherModal(user)">
            <td class="p-2">{{user.name}}</td>
            <td class="p-2">{{user.email}}</td>
            <td class="p-2">{{user.latitude}},{{user.longitude}}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <weather ref="weatherModal" />
</template>

<script>
import Weather from '@/components/Weather.vue';

export default {
  components: { Weather },
  data: () => ({
    users: null
  }),

  mounted() {
    this.fetchData()
  },

  methods: {
    async fetchData() {
      // first get user list from backend
      const response = await (await fetch("http://localhost/")).json();

      if (response?.message === "all systems are a go") {
        this.users = response.users;
      }
    },

    showWeatherModal(user) {
      this.$refs.weatherModal.show(user)
    }
  }
}
</script>