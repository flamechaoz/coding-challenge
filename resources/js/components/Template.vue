<template>
  <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      :clipped="$vuetify.breakpoint.lgAndUp"
      app
      v-if="user_.logged_in"
    >
      <v-list dense>
        <template v-for="item in items">
          <v-list-item
            :key="item.text"
            link
            :to="item.to"
            class="text-decoration-none"
          >
            <v-list-item-action class="mr-3">
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>
                {{ item.text }}
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </template>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      :clipped-left="$vuetify.breakpoint.lgAndUp"
      app
      color="rgb(32,56,100)"
      dark
      v-if="user_.logged_in"
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title
        style="width: 300px"
        class="ml-0 pl-4"
      >
        <span class="hidden-sm-and-down">Watch</span>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <!-- <v-btn icon>
        <v-icon>mdi-apps</v-icon>
      </v-btn> -->
      <!-- <v-btn icon class="mr-5">
        <v-icon>mdi-bell</v-icon>
      </v-btn> -->

      <v-menu
        v-model="menu"
        :close-on-content-click="true"
        :offset-y="true"
      >
        <template v-slot:activator="{ on, attrs }">
            <v-btn
                icon
                x-large
                v-bind="attrs"
                v-on="on"
                class="mr-3"
            >
                <v-icon>mdi-account-circle</v-icon>
            </v-btn>
            
        </template>
        <v-card class="pt-3">
            <span class="text-subtitle-2 mx-3 text-secondary"><b>Signed in as</b></span>
            <br>
            <span class="text-subtitle-2 mx-3"><b>John Doe</b></span>
            <hr class="mt-2 mb-0">
            <v-list class="py-0">
                <v-list-item @click="logout">Logout</v-list-item>
            </v-list>
        </v-card>

      </v-menu>

    </v-app-bar>

    <v-main>
      <router-view></router-view>
    </v-main>

  </v-app>
</template>

<script>
  export default {
    
    props: {
      source: String,
    },

    data: () => ({
        menu: false,
        dialog: false,
        drawer: null,
        items: [
            { icon: 'mdi-view-dashboard', text: 'Health Monitoring Dashboard', to: '/dashboard' },
            { icon: 'mdi-file-question', text: 'Health Screening Dashboard', to: '/questionnaire_dashboard' },
            { icon: 'mdi-cog', text: 'Threshold', to: '/threshold' },
            { icon: 'mdi-school', text: 'Students', to: '/students' },
            { icon: 'mdi-plus-box-multiple', text: 'Add Data', to: '/add_data' },
        ],
    }),

    computed: {
        user_: function() {
            return this.$store.state.currentUser.user_data;
        }
    },

    methods: {

      logout(){
        
        fetch("/api/auth/logout", {
          method: "GET",
          headers: {
            Authorization: this.user_.token
          }
        })
        .then(response => {
          if (response.status !== 200) {
            console.log(response);
          } else {
            localStorage.removeItem("_watch_user");
            Swal.fire(
                "Success",
                "Logout successful",
                "success"
            );
            this.$store.commit("currentUser/setUserData", {
                logged_in: false,
                token: "",
                expires_at: ""
            });
            this.$router.push("/login");
          }
        })
        .catch(function(err) {
            console.log(err);
        });

      }

    },

    mounted() {
    }

  }
</script>