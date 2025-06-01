<template>
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold text-gray-700">
                Créer une Université
            </h2>
        </div>

        <!-- Barre de progression -->
        <div class="flex mb-4">
            <div
                v-for="(step, index) in steps"
                :key="index"
                class="flex-1 text-center p-2 border-b-4"
                :class="{
                    'border-blue-500 text-blue-500': index === currentStep,
                    'border-gray-300 text-gray-400': index !== currentStep,
                }"
            >
                {{ step.name }}
            </div>
        </div>

        <!-- Contenu des étapes -->
        <div v-if="currentStep === 0">
            <h3 class="text-lg font-semibold mb-2">Informations Université</h3>
            <input
                v-model="form.university_name"
                type="text"
                placeholder="Nom de l'université"
                class="w-full p-2 border rounded-md mb-2"
            />
            <input
                v-model="form.address"
                type="text"
                placeholder="Adresse"
                class="w-full p-2 border rounded-md mb-2"
            />
        </div>

        <div v-if="currentStep === 1">
            <h3 class="text-lg font-semibold mb-2">
                Informations Administrateur
            </h3>
            <input
                v-model="form.admin_name"
                type="text"
                placeholder="Nom Admin"
                class="w-full p-2 border rounded-md mb-2"
            />
            <input
                v-model="form.admin_email"
                type="email"
                placeholder="Email Admin"
                class="w-full p-2 border rounded-md mb-2"
            />
            <input
                v-model="form.admin_password"
                type="password"
                placeholder="Mot de passe"
                class="w-full p-2 border rounded-md mb-2"
            />
        </div>

        <div v-if="currentStep === 2">
            <h3 class="text-lg font-semibold mb-2">Choisir un Abonnement</h3>
            <select
                v-model="form.subscription_id"
                class="w-full p-2 border rounded-md"
            >
                <option
                    v-for="subscription in subscriptions"
                    :key="subscription.id"
                    :value="subscription.id"
                >
                    {{ subscription.name }} - {{ subscription.price }}$
                </option>
            </select>
        </div>

        <div v-if="currentStep === 3">
            <h3 class="text-lg font-semibold mb-2">Vérification des Données</h3>
            <p><strong>Université:</strong> {{ form.university_name }}</p>
            <p><strong>Adresse:</strong> {{ form.address }}</p>
            <p>
                <strong>Admin:</strong> {{ form.admin_name }} ({{
                    form.admin_email
                }})
            </p>
            <p><strong>Abonnement:</strong> {{ selectedSubscription }}</p>
        </div>

        <!-- Boutons de navigation -->
        <div class="flex justify-between mt-4">
            <button
                v-if="currentStep > 0"
                @click="prevStep"
                class="bg-gray-300 text-gray-700 px-4 py-2 rounded"
            >
                Précédent
            </button>

            <button
                v-if="currentStep < steps.length - 1"
                @click="nextStep"
                class="bg-blue-500 text-white px-4 py-2 rounded"
            >
                Suivant
            </button>

            <button
                v-if="currentStep === steps.length - 1"
                @click="submitForm"
                class="bg-green-500 text-white px-4 py-2 rounded"
            >
                Soumettre
            </button>
        </div>

        <!-- Message de succès -->
        <p v-if="message" class="text-green-600 mt-4">{{ message }}</p>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            currentStep: 0,
            message: "",
            steps: [
                { name: "Université" },
                { name: "Admin" },
                { name: "Abonnement" },
                { name: "Confirmation" },
            ],
            form: {
                university_name: "",
                address: "",
                admin_name: "",
                admin_email: "",
                admin_password: "",
                subscription_id: null,
            },
            subscriptions: [],
        };
    },
    computed: {
        selectedSubscription() {
            return (
                this.subscriptions.find(
                    (sub) => sub.id === this.form.subscription_id
                )?.name || "Non choisi"
            );
        },
    },
    methods: {
        async fetchSubscriptions() {
            try {
                const response = await axios.get("/api/subscriptions");
                this.subscriptions = response.data;
            } catch (error) {
                console.error("Erreur de récupération des abonnements:", error);
            }
        },
        nextStep() {
            if (this.currentStep < this.steps.length - 1) this.currentStep++;
        },
        prevStep() {
            if (this.currentStep > 0) this.currentStep--;
        },
        async submitForm() {
            try {
                const response = await axios.post(
                    "/api/universities",
                    this.form
                );
                this.message = response.data.message;
            } catch (error) {
                console.error("Erreur d'envoi:", error);
            }
        },
    },
    mounted() {
        this.fetchSubscriptions();
    },
};
</script>

<style scoped>
/* Ajoute un style pour la barre de progression */
</style>
