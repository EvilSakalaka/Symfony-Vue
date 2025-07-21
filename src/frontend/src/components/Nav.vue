<script setup>
    import { RouterLink } from "vue-router";
    import { lang } from '../languageImporter';
</script>
<template>
    <ul class="nav flex-column col-2">
      <li class="nav-item">
        <RouterLink to="/" class="nav-link">{{ lang('homepage') }}</RouterLink>
      </li>
      <li class="nav-item">
        <RouterLink to="/about" class="nav-link">{{ lang('aboutpage') }}</RouterLink>
      </li>
    </ul>
</template>

<style scoped>
.nav {
  background-color: #f8f9fa;
  padding: 1rem;
}

.nav-item {
  margin-bottom: 0.5rem;
}

.nav-link {
  color: #007bff;
  text-decoration: none;
}

.nav-link:hover {
  text-decoration: underline;
}
</style>