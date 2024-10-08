import { defineStore } from 'pinia';
import axios from 'axios';

let handleError = function (text, error, state) {
    console.error(text + ':', error);
    state.errorMessage = text + ' Error: ' + error.response.data.error;
}

export const useEmailStore = defineStore('emailStore', {
    state: () => ({
        emails: [],
        errorMessage: '',
    }),
    actions: {
        async fetchEmails() {
            try {
                const response = await axios.get('/api/emails');
                this.emails = response.data;
            } catch (error) {
                handleError('Failed to fetch emails', error, this)

                throw error;
            }
        },
        async createEmail(email) {
            try {
                await axios.post('/api/emails', email);
                await this.fetchEmails();
            } catch (error) {
                handleError('Failed to create email', error, this)

                throw error;
            }
        },
        async updateEmail(email) {
            try {
                await axios.put(`/api/emails/${email.id}`, email);
                await this.fetchEmails();
            } catch (error) {
                handleError(error, this)

                throw error;
            }
        },
        async deleteEmail(emailId) {
            try {
                await axios.delete(`/api/emails/${emailId}`);
                await this.fetchEmails();
            } catch (error) {
                handleError('Failed to delete email', error, this)

                throw error;
            }
        },
    },
});
