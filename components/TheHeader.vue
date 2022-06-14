<template>
  <div id="header">
    <v-row>
      <v-col cols="12" lg="3" md="3" sm="12">
        <router-link id="logo" to="/">
            <h2>Shawning Shop</h2>
        </router-link>
      </v-col>
      <v-col cols="12" lg="6" md="6" sm="12">
        <input type="text" v-model="input" placeholder="Search.." id="search" class="bg-white" active-class="bg-red">
        <div>
          <v-chip-group
          active-class="primary--text"
          column
          >
            <v-chip class="bg-white"
              v-for="tag in tags"
              :key="tag"
            >
              {{ tag }}
            </v-chip>
          </v-chip-group>
          {{input}}
        </div>
      </v-col>
      <v-col cols="12" lg="3" md="3" sm="12">
        <TheNavigation></TheNavigation>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import TheNavigation from './TheNavigation.vue'
import { ref } from 'vue'

export default {
  setup () {
    return {
      input: ref('')
    }
  },

  components: {
    TheNavigation
  },

  data: () => ({
    tags: [
      'Work',
      'Home Improvement',
      'Vacation',
      'Food',
      'Drawers',
      'Shopping',
      'Art',
      'Tech'
    ]
  })
}
</script>

<style lang="scss">
@import '../assets/scss/header.scss';
</style>>
