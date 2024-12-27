<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <form @submit.prevent="requestChange" class="mb-4">
                            <h2 class="h4 mb-3">Запрос на изменение настройки</h2>
                            <div class="mb-3">
                                <label for="newValue" class="form-label">Новое значение</label>
                                <input
                                    v-model="newValue"
                                    type="text"
                                    id="newValue"
                                    class="form-control"
                                    placeholder="Введите новое значение"
                                />
                            </div>
                            <div class="mb-3">
                                <label for="method" class="form-label">Выберите метод</label>
                                <select
                                    v-model="method"
                                    id="method"
                                    class="form-select"
                                >
                                    <option value="email">Электронная почта</option>
                                    <option value="sms">СМС</option>
                                    <option value="telegram">Telegram</option>
                                </select>
                            </div>
                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                            >
                                Отправить запрос
                            </button>
                        </form>

                        <form @submit.prevent="confirmChange">
                            <h2 class="h4 mb-3">Подтвердите изменение</h2>
                            <div class="mb-3">
                                <label for="confirmationCode" class="form-label">Код подтверждения</label>
                                <input
                                    v-model="confirmationCode"
                                    type="text"
                                    id="confirmationCode"
                                    class="form-control"
                                    placeholder="Введите код подтверждения"
                                />
                            </div>
                            <button
                                type="submit"
                                class="btn btn-success w-100"
                            >
                                Подтвердить
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            newValue: '',
            method: 'email',
            confirmationCode: '',
            changeId: null,
        };
    },
    methods: {
        async requestChange() {
            const response = await axios.post('/api/settings/update', {
                new_value: this.newValue,
                method: this.method,
            });
            this.changeId = response.data.change_id;
        },
        async confirmChange() {
            await axios.post(`/api/settings/confirm/${this.changeId}`, {
                confirmation_code: this.confirmationCode,
            });
            alert('Настройка успешно обновлена!');
        },
    },
};
</script>

<style>
body {
    background-color: #f8f9fa;
}
</style>
