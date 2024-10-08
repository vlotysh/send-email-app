<template>
    <div class="form-container">
        <form @submit.prevent="submitForm" class="email-form">
            <div class="form-group">
                <label for="recipient">Recipient:</label>
                <input type="email" v-model="localEmail.recipient" required />
            </div>
            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" v-model="localEmail.subject" required />
            </div>
            <div class="form-group">
                <label for="body">Body:</label>
                <textarea v-model="localEmail.body" required></textarea>
            </div>
            <div class="form-group">
                <label for="scheduled_time">Scheduled Time:</label>
                <input type="datetime-local" v-model="localEmail.scheduled_time" required />
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" v-model="localSendNow" /> Send Now
                </label>
            </div>
            <div class="form-actions">
                <button type="submit" class="submit-button">Submit</button>
                <button type="button" @click="$emit('closeForm')" class="cancel-button">Cancel</button>
            </div>
            <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
        </form>
    </div>
</template>

<script>
import { ref, watch } from 'vue';
import { useEmailStore } from '../../stores/emailStore';

export default {
    props: {
        email: Object,
    },
    setup(props, {emit}) {
        const emailStore = useEmailStore();
        const localEmail = ref({...props.email});
        const localSendNow = ref(false);
        const errorMessage = ref('');

        watch(props.email, (newEmail) => {
            localEmail.value = {...newEmail};
        });

        const submitForm = async () => {
            errorMessage.value = '';

            let data = localEmail.value;
            data.scheduled_time = new Date(data.scheduled_time).toISOString();

            try {
                if (localEmail.value.id) {
                    await emailStore.updateEmail(localEmail.value);
                } else {
                    await emailStore.createEmail({ ...data, send_now: localSendNow.value });
                }
                emit('formSubmitted');
                emit('closeForm')
            } catch (error) {
                errorMessage.value = emailStore.errorMessage;
            }
        };

        return {localEmail, localSendNow, submitForm, errorMessage};
    },
};
</script>

<style scoped>

</style>
