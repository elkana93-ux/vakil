<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps({
  link:     { type: String, default: null },
  name:     { type: String, default: null },
  notFound: { type: Boolean, default: false },
})

const form = useForm({ phone: '' })

const submit = () => {
  form.post(route('join.send'))
}
</script>

<template>
  <GuestLayout title="הצטרפות לעץ המשפחה">
    <Head title="הצטרפות לעץ המשפחה" />

    <template v-if="link">
      <p class="auth-note">שלום <strong>{{ name }}</strong> 👋</p>
      <a :href="link" class="auth-btn" style="display:block;text-align:center;text-decoration:none">
        לחצו כאן ליצירת משתמש וסיסמה
      </a>
    </template>

    <template v-else>
      <p class="auth-note">
        הזינו את מספר הטלפון שלכם ונראה לכם קישור אישי ליצירת משתמש וסיסמה.
      </p>

      <div v-if="notFound" class="auth-error" style="margin-bottom:12px">
        המספר הזה לא נמצא ברשימה. בדקו שהקלדתם נכון, או פנו לאלקנה.
      </div>

      <form @submit.prevent="submit">
        <div class="auth-field">
          <label class="auth-label" for="phone">מספר טלפון</label>
          <input id="phone" type="tel" class="auth-input" v-model="form.phone"
            required autofocus autocomplete="tel" dir="ltr" placeholder="050-0000000" />
          <div v-if="form.errors.phone" class="auth-error">{{ form.errors.phone }}</div>
        </div>

        <button type="submit" class="auth-btn" :disabled="form.processing">
          {{ form.processing ? 'בודק...' : 'הציגו לי את הקישור' }}
        </button>
      </form>
    </template>

    <div class="auth-foot">
      <Link :href="route('login')" class="auth-link">כבר יש לי משתמש ← התחברות</Link>
    </div>
  </GuestLayout>
</template>
