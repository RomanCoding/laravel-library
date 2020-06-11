<template>
    <div>
        <v-toolbar flat color="white">
            <v-spacer></v-spacer>
        </v-toolbar>
        <v-layout row wrap>
            <v-flex xs12 sm6>
                <v-text-field v-if="settings"
                              label="Mail username"
                              outline
                              style="margin-right: 0.5rem;"
                              v-model="settings['mail.username']"
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm6>
                <v-text-field v-if="settings"
                              label="Mail password"
                              outline
                              :type="settings['mail.password'] ? 'password' : 'text'"
                              v-model="settings['mail.password']"
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm6>
                <v-text-field v-if="settings"
                              label="Mail from: Address"
                              outline
                              v-model="settings['mail.from.address']"
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm6>
                <v-text-field v-if="settings"
                              label="Mail from: Name"
                              outline
                              v-model="settings['mail.from.name']"
                ></v-text-field>
            </v-flex>
            <v-flex xs12 sm6>
                <v-text-field v-if="settings"
                              label="Mail host"
                              outline
                              v-model="settings['mail.host']"
                ></v-text-field>
            </v-flex>

            <button class="btn btn-info" @click="saveSettings">Save settings</button>
        </v-layout>
        <v-snackbar v-model="snack" :timeout="3000" :color="snackColor">
            {{ snackText }}
            <v-btn flat @click="snack = false">Close</v-btn>
        </v-snackbar>
    </div>
</template>
<script>
  export default {
    data: () => ({
      snack: false,
      snackText: '',
      snackColor: '',
      dialog: false,
      settings: [],
    }),
    computed: {},
    watch: {
      dialog(val) {
        val || this.close()
      }
    },
    created() {
      this.fetchSettings();
    },
    methods: {
      fetchSettings() {
        let self = this;
        axios.get('/settings')
          .then(r => self.settings = r.data)
          .catch(e => {
            alert('Fetching failed, try again later...');
            // self.snack = true;
            // self.snackColor = 'error';
            // self.snackText = 'Fetching failed, try again later...';
          });
      },
      saveSettings() {
        axios.post('/settings', {
          settings: {
            'mail.username': this.settings['mail.username'],
            'mail.password': this.settings['mail.password'],
            'mail.from.address': this.settings['mail.from.address'],
            'mail.from.name': this.settings['mail.from.name'],
            'mail.host': this.settings['mail.host'],
          }
        })
          .then(r => {
            alert('Settings saved!');
            // self.snack = true;
            // self.snackColor = 'success';
            // self.snackText = 'Settings saved!';
          })
          .catch(e => {
            alert('Refresh page and try again...');
            // self.snack = true;
            // self.snackColor = 'error';
            // self.snackText = 'Refresh page and try again...';
          });
      }
    }
  }
</script>
