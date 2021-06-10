<template>
    <v-container
      class="fill-height"
      fluid
    >
      <v-row
        align="center"
        justify="center"
      >
        <v-col
          cols="12"
          sm="8"
          md="4"
        >
          <!-- <alert-errors :form="this.login_credentials" /> -->
          <v-card class="elevation-12">
            <v-form
              @submit.prevent="login"
              @keydown="login_credentials.onKeydown($event)"
            >
              <v-toolbar
                color="primary"
                dark
                flat
              >
                <v-toolbar-title>Login form</v-toolbar-title>
              </v-toolbar>
              <v-card-text class="pb-0">
                <v-text-field
                  label="Login"
                  prepend-inner-icon="mdi-account"
                  type="text"
                  outlined
                  v-model="login_credentials.usrnm"
                  :error="login_credentials.errors.has('usrnm')"
                  :error-messages="formHasError(login_credentials, 'usrnm')"
                ></v-text-field>
                <v-text-field
                  id="password"
                  label="Password"
                  prepend-inner-icon="mdi-lock"
                  :append-icon="show_pass ? 'mdi-eye' : 'mdi-eye-off'"
                  :type="show_pass ? 'text' : 'password'"
                  @click:append="show_pass = !show_pass"
                  outlined
                  v-model="login_credentials.pwd"
                  :error="login_credentials.errors.has('pwd')"
                  :error-messages="formHasError(login_credentials, 'pwd')"
                ></v-text-field>
                <v-checkbox class="mt-0 pt-0" v-model="login_credentials.keep_logged_in" label="Keep me logged in"></v-checkbox>
              </v-card-text>
              <v-card-actions class="pt-0">
                <v-spacer></v-spacer>
                <v-btn
                color="primary"
                type="submit"
                :disabled="login_credentials.busy"
                :loading="login_credentials.busy"
                >
                Login
                <template v-slot:loader>
                  <span>Please wait...</span>
                </template>
                </v-btn>
              </v-card-actions>
            </v-form>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
</template>

<script>

import { Form, HasError } from "vform";
import AlertErrors from "../Alert/Error";

import Vue from "vue";

Vue.component(HasError.name, HasError);
Vue.component(AlertErrors.name, AlertErrors);

export default {

  props: {

    source: String,

  },

  data: () => ({

    login_credentials: new Form({
      usrnm: "",
      pwd: "",
      keep_logged_in: false
    }),

    show_pass: false

  }),

  methods: {

    async login(){

      await this.login_credentials
        .post("api/auth/login")
        .catch(err => console.log(err))
        .then(data => {
          if(data.data.success){
            const _user = {
              logged_in: true,
              token: "Bearer " + data.data.access_token,
              expires_at: data.data.expires_at
            };

            localStorage.setItem(
              "_watch_user",
              window.btoa(
                "WATCH-" +
                window.btoa(
                  unescape(
                    encodeURIComponent(
                      JSON.stringify(_user)
                    )
                  )
                )
              )
            );

            window.axios.defaults.headers.common["Authorization"] =
              _user.token;
            this.$store.commit("currentUser/setUserData", _user);

            Swal.fire("Login successful", data.data.message, "success");

            this.$router.push("/dashboard").catch((err) => {
              throw new Error(`Problem handling something: ${err}.`);
            });

          }
        });

    },
    formHasError(form, key) {
      return form.errors.has(key) ? form.errors.get(key) : "";
    }

  },

  mounted() {
    
  }

}
</script>