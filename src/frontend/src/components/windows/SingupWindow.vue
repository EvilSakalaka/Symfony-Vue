<script setup>
    import { lang } from '@/languageImporter';
    import { ref } from 'vue';
    defineEmits(['close']);
    
    const formData = ref({
        username: '',
        password: ''
    });

    const error = ref(null);
    const apiUrl = 'http://localhost:8080/auth/signup';

    const handleSubmit = async () => {
        try {
            console.log('Submitting signup form with data:', formData.value);
            console.log('JSON form data:', JSON.stringify(formData.value));

            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData.value)
            });
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            console.log('Signup response data:', data);
        } catch (err) {
            error.value = `Hiba történt: ${err.message}`;
        }
    };
</script>

<template>
  <form class="modal" @submit.prevent="handleSubmit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">{{ lang('signupbutton')}}</h1>
        <button type="button" class="btn-close" aria-label="Close" @click="$emit('close')"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="username" class="form-label">{{ lang('username')}}</label>
          <input type="text" class="form-control" id="username" :placeholder="lang('usernamePlaceholder')" v-model="formData.username" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">{{ lang('password')}}</label>
          <input type="password" class="form-control" id="password" :placeholder="lang('passwordPlaceholder')" v-model="formData.password" required>
        </div>
        <div class="mb-3" v-if="error">
          <p>{{ error }}</p>
        </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="$emit('close')">{{ lang('closebutton') }}</button>
        <button type="submit" class="btn btn-primary">{{ lang('signupbutton')}}</button>
      </div>
    </div>
  </div>
</form>
</template>

<style scoped>
.modal
{
  display: block !important;
}
</style>