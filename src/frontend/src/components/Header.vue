<script setup>
    import LoginWindow from "@/components/windows/LoginWindow.vue";
    import SignupWindow from "@/components/windows/SingupWindow.vue";
    import { useRoute } from 'vue-router';
    import { computed, ref } from 'vue';
    import { lang } from '@/languageImporter';

    const route = useRoute();
    const pageTitle = computed(() => route.name || 'Jász Plasztik PDCS');

    const loginWindowState = ref(false);
    const signupWindowState = ref(false);
    
    

    function toggleLogin() {
        // Logic for handling login can be added here

        console.log('Login button clicked');
        loginWindowState.value = !loginWindowState.value;
    }

    function toggleSignup() {
        // Logic for handling signup can be added here

        console.log('Signup button clicked');
        // Implement signup logic if needed
        signupWindowState.value = !signupWindowState.value;
    }
</script>

<template>
    <LoginWindow v-if="loginWindowState" @close="toggleLogin"/>
    <SignupWindow v-if="signupWindowState" @close="toggleSignup"/>
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

    <div class="col-3 row">
        <!--<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            {{ lang('loginbutton') }}
        </button>-->
        <p class="col-auto" @click.prevent="toggleLogin">{{ lang('loginbutton') }}</p>
        <p class="col-auto">/</p>
        <p class="col-auto" @click.prevent="toggleSignup">{{ lang('signupbutton') }}</p>
    </div>
  </header>
</template>

<style scoped>
header {
  background-color: #f8f9fa;
  padding: 1rem;
}

header img {
  max-height: 80px;
}

header h1 {
  font-size: 1.5rem;
  margin: 0;
}
</style>