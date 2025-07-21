<script setup>
import { RouterLink, RouterView, useRoute } from "vue-router";
import { computed } from "vue";
const route = useRoute();

const pageTitle = computed(() => route.name || "Jász Plasztik PDCS");

</script>

<template>
  <header class="row align-items-center">
    <img
      alt="JP logo"
      class="col-2"
      src="@/assets/jp_logo.svg"
      height="80"
    />

    <div class="col text-center">
      <h1>{{ pageTitle }}</h1>
    </div>
    <div class="col-auto">

    </div>
  </header>
  <div class="row">
    <ul class="nav flex-column col-2">
      <li class="nav-item">
        <RouterLink to="/" class="nav-link">Home</RouterLink>
      </li>
      <li class="nav-item">
        <RouterLink to="/about" class="nav-link">About</RouterLink>
      </li>
    </ul>
    <div class="col">
      <RouterView />
    </div>
  </div>
</template>

<style scoped>
header {
  background-color: #002777;
  color: white;
  padding : 5px;
}
nav {
  background-color: #00349b;
  padding: 10px;
}
nav a {
  color: white;
  text-decoration: none;
  margin-right: 10px;
  display: block;
}
</style>
