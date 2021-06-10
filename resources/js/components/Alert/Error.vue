<template>
    <v-alert
        v-if="form.errors.any()"
        type="error"
        class="alert alert-danger alert-dismissible"
        role="alert"
    >
        <div class="d-flex justify-between">
            <div v-if="message" v-html="message" />
            <v-spacer />
            <button
                v-if="dismissible"
                type="button"
                class="close"
                aria-label="Close"
                @click="dismiss"
            >
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <ul>
            <li
                v-for="(error, index) in form.errors.flatten()"
                :key="index"
                v-html="error"
            />
        </ul>
    </v-alert>
</template>

<script>
import Alert from "./Alert";
export default {
    name: "alert-errors",
    extends: Alert,
    props: {
        message: {
            type: String,
            default: "以下のエラーを修正してください: "
        }
    }
};
</script>