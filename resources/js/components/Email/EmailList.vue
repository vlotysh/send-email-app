<template>
    <div>
        <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
        <button @click="showEmailForm = true" class="create-button">Create Email</button>
        <EmailForm v-if="showEmailForm" :email="email" @closeForm="closeForm" @formSubmitted="reloadEmails"/>
        <div>
            <h2>Scheduled Emails</h2>
            <ul v-if="emails && emails.length > 0" class="email-list">
                <li v-for="email in emails" :key="email.id" class="email-item">
                    <p><strong>Recipient:</strong> {{ email.recipient }}</p>
                    <p><strong>Subject:</strong> {{ email.subject }}</p>
                    <p><strong>Scheduled Time:</strong> {{ new Date(email.scheduled_time).toLocaleString() }}</p>
                    <p><strong>Body:</strong> {{ email.body }}</p>
                    <p><strong>Status:</strong> {{ email.status }}</p>
                    <!-- check on pending status, edit only for pending -->
                    <button v-if="email.status === 'pending'" @click="editEmail(email)" class="edit-button">Edit</button>
                    <button @click="deleteEmail(email.id)" class="delete-button">Delete</button>
                </li>
            </ul>
            <p v-else>No scheduled emails found.</p>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useEmailStore } from '../../stores/emailStore';
import EmailForm from './EmailForm.vue';

export default {
    components: {
        EmailForm,
    },
    setup() {
        const emailStore = useEmailStore();
        const emails = ref([]);
        const showEmailForm = ref(false);
        const email = ref({ recipient: '', subject: '', body: '', scheduled_time: '' });
        const errorMessage = ref('');

        const loadEmails = async () => {
            await emailStore.fetchEmails();
            emails.value = emailStore.emails;
        };

        onMounted(loadEmails);

        const editEmail = (emailToEdit) => {
            errorMessage.value = '';
            email.value = { ...emailToEdit };
            showEmailForm.value = true;
        };

        const deleteEmail = async (emailId) => {
            errorMessage.value = '';

            try {
                await emailStore.deleteEmail(emailId);
                await loadEmails();
            } catch (error) {
                errorMessage.value = emailStore.errorMessage;
            }
        };

        const closeForm = () => {
            showEmailForm.value = false;
        };

        const reloadEmails = async () => {
            await loadEmails();
            showEmailForm.value = false;
        };

        return {
            emails,
            showEmailForm,
            email,
            editEmail,
            deleteEmail,
            closeForm,
            reloadEmails,
            errorMessage,
        };
    },
};
</script>
