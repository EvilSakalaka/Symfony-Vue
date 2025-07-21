<script setup>
  import Header from "@/components/Header.vue";
  import Nav from "@/components/Nav.vue";
  import { RouterView } from "vue-router";
</script>

<template>
  <Header />
  <div class="row">
    <div class="col-2">
      <Nav />
    </div>
    <div class="col">
      <RouterView />
    </div>
  </div>
</template>