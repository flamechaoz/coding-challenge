<template>
    <v-container fluid >
        <v-row class="justify-content-center">
            <v-col cols="12" sm="10" md="8">
                <v-card>
                    <v-card-title>
                        calendar
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12" sm="10" md="5" lg="5">
                                <label>Event</label>
                                <v-text-field
                                    v-model="event_name"
                                    placeholder=""
                                    outlined
                                    dense
                                ></v-text-field>
                                <v-row>
                                    <v-col>
                                        <label>From</label>
                                        <v-menu
                                            v-model="menu_from"
                                            :close-on-content-click="false"
                                            transition="scale-transition"
                                            offset-y
                                            min-width="auto"
                                        >
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-text-field
                                                    dense
                                                    outlined
                                                    v-model="date_from"
                                                    prepend-inner-icon="mdi-calendar"
                                                    readonly
                                                    v-bind="attrs"
                                                    v-on="on"
                                                ></v-text-field>
                                            </template>
                                            <v-date-picker
                                                v-model="date_from"
                                                no-title
                                                scrollable
                                                @input="menu_from = false"
                                            >
                                            <v-spacer></v-spacer>
                                            </v-date-picker>
                                        </v-menu>
                                    </v-col>
                                    <v-col>
                                        <label>To</label>
                                        <v-menu
                                            ref="menu2"
                                            v-model="menu_to"
                                            :close-on-content-click="false"
                                            transition="scale-transition"
                                            offset-y
                                            min-width="auto"
                                        >
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-text-field
                                                    dense
                                                    outlined
                                                    v-model="date_to"
                                                    prepend-inner-icon="mdi-calendar"
                                                    readonly
                                                    v-bind="attrs"
                                                    v-on="on"
                                                ></v-text-field>
                                            </template>
                                            <v-date-picker
                                                v-model="date_to"
                                                no-title
                                                scrollable
                                                @input="menu_to = false"
                                            >
                                            <v-spacer></v-spacer>
                                            </v-date-picker>
                                        </v-menu>
                                    </v-col>
                                </v-row>
                                <v-row class="px-3">
                                    <v-checkbox dense v-for="(day, index) in days" :key="index" :label="day" v-model="selected_days" :value="day" class="mr-2" />
                                </v-row>
                                <v-btn color="primary" @click="submitEvent()">Save</v-btn>
                            </v-col>
                            <v-col cols="12" sm="10" md="7" lg="7">
                                <!-- {{ $refs.calendar.title }} -->
                                <v-sheet height="800">
                                    <v-calendar
                                        ref="calendar"
                                        :events="events"
                                        color="primary"
                                        type="month"
                                        event-start="event_date"
                                        event-name="event_name"
                                        @change="getEvents"
                                    ></v-calendar>
                                </v-sheet>
                            </v-col>
                        </v-row>

                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import { Form, HasError } from "vform";

export default {
    
    data() {
        return {
            focus: '',
            event_name: '',
            events: [],

            days: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],

            date_from: null,
            date_to: null,

            selected_days: [],
            dates: [],

            menu_from: false,
            menu_to: false,
        }
    },

    methods: {

        getDaysArray(startDate, endDate) {

            var now = startDate.clone();
            var dates = [];
    
            while (now.isSameOrBefore(endDate)) {
                dates.push(now.format('YYYY-MM-DD'));
                now.add(1, 'days');
            }
            this.dates = dates;
            // return dates;
        },

        filterDatesByDay(){

            var days = this.selected_days;
            this.dates =  this.dates.filter(function(date) {
                return days.includes(moment(date).format('ddd'));
            });

        },

        submitEvent(){

            var startDate = moment(this.date_from);
            var endDate = moment(this.date_to);

            this.getDaysArray(startDate, endDate);
            this.filterDatesByDay();

            axios
                .post('/api/event/saveEvent', {
                    dates: this.dates,
                    name: this.event_name
                })
                .catch(err => Swal.fire("Server error", err, "error"))
                .then(data => {
                    if(data.data.success){
                        Toast.fire('Event successfully saved');
                        this.events = data.data.events
                    }
                    else{
                        Toast.fire('Server error.');
                    }
                });

        },

        async getEvents(){

            await axios
                .get("/api/event")
                .catch(err => console.log(err))
                .then(data => {
                    this.events = data.data
                });

        }

    },

    mounted() {
    }

}
</script>
<style scoped>
    label{
        margin-bottom: 0px;
    }
</style>