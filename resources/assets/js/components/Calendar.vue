<template>
    <div class="row">
        <div class="modal fade show" tabindex="-1" role="dialog" style="display:block;" v-if="selectedEvent">
            <div :class="{'modal-dialog':1, 'modal-dialog-centered':1, 'modal-sm': !selectedEvent.description}" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-text="selectedEvent.title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{ selectedEvent.description }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" @click="deleteSelected" v-if="isAdmin">Delete</button>
                        <button type="button" class="btn btn-secondary" @click="selectedEvent = null;">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="calendar-controls">

            <div v-if="message" class="notification is-success">{{ message }}</div>

            <div class="box">

                <h4 class="title is-5">Calendar options</h4>

                <div class="field">
                    <label class="label">Period UOM</label>
                    <div class="control">
                        <div class="select">
                            <select v-model="displayPeriodUom" class="form-control">
                                <option>month</option>
                                <option>week</option>
                                <option>year</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Period Count</label>
                    <div class="control">
                        <div class="select">
                            <select v-model="displayPeriodCount" class="form-control">
                                <option :value="1">1</option>
                                <option :value="2">2</option>
                                <option :value="3">3</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Starting day of the week</label>
                    <div class="control">
                        <div class="select">
                            <select v-model="startingDayOfWeek" class="form-control">
                                <option
                                        v-for="(d, index) in dayNames"
                                        :value="index"
                                        :key="index">{{ d }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>


            </div>

            <div class="box" v-if="isAdmin">
                <div class="field">
                    <label class="label">Title</label>
                    <div class="control">
                        <input v-model="newEventTitle" class="form-control" type="text">
                    </div>
                </div>

                <div class="field">
                    <label class="label">Description (optional)</label>
                    <div class="control">
                        <textarea v-model="newEventDescription" class="form-control"
                                  placeholder="This field is optional"></textarea>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Start date</label>
                    <div class="control">
                        <input v-model="newEventStartDate" class="form-control" type="date">
                    </div>
                </div>

                <div class="field">
                    <label class="label">End date</label>
                    <div class="control">
                        <input v-model="newEventEndDate" class="form-control" type="date">
                    </div>
                </div>

                <div class="field">
                    <label class="label">Theme</label>
                    <div class="control">
                        <select v-model="newEventTheme" class="form-control">
                            <option value="">Default</option>
                            <option value="orange">Orange</option>
                            <option value="red">Red</option>
                            <option value="purple">Purple</option>
                        </select>
                    </div>
                </div>

                <button class="btn btn-primary mt-1" @click="addEvent">Add Event</button>
            </div>

        </div>
        <div class="calendar-parent">
            <calendar-view
                    :events="events"
                    :show-date="showDate"
                    :time-format-options="{hour: 'numeric', minute:'2-digit'}"
                    :disable-past="disablePast"
                    :disable-future="disableFuture"
                    :show-event-times="showEventTimes"
                    :display-period-uom="displayPeriodUom"
                    :display-period-count="displayPeriodCount"
                    :starting-day-of-week="startingDayOfWeek"
                    :class="themeClasses"
                    @click-date="onClickDay"
                    @click-event="onClickEvent"
                    @show-date-change="setShowDate"
            />
        </div>
    </div>
</template>
<script>
    import CalendarView from 'vue-simple-calendar'
    import CalendarMathMixin from "vue-simple-calendar/dist/calendar-math-mixin.js"
    require("vue-simple-calendar/dist/static/css/default.css")
    require("vue-simple-calendar/dist/static/css/holidays-us.css")
    export default {
        components: {
            CalendarView
        },
        mixins: [CalendarMathMixin],
        data() {
            return {
                user: null,

                selectedEvent: null,

                showDate: this.thisMonth(1),
                message: "",
                startingDayOfWeek: 0,
                disablePast: false,
                disableFuture: false,
                displayPeriodUom: "month",
                displayPeriodCount: 1,
                showEventTimes: true,
                newEventTitle: "",
                newEventDescription: "",
                newEventStartDate: "",
                newEventEndDate: "",
                newEventTheme: "",
                useDefaultTheme: true,
                useHolidayTheme: true,
                events: [],
            }
        },
        created() {
            let self = this;
            axios.get('/user').then(r => self.user = r.data);
            axios.get('/events').then(r => {
                self.events = r.data.map(event => {
                    return {
                        id: event.id,
                        startDate: event.start_date,
                        endDate: event.end_date,
                        title: event.title,
                        description: event.description,
                        classes: event.theme,
                    }
                });
            });
        },
        computed: {
            isAdmin() {
                return (this.user && this.user.access_level === 3);
            },
            userLocale() {
                return this.getDefaultBrowserLocale
            },
            dayNames() {
                return this.getFormattedWeekdayNames(this.userLocale, "long", 0)
            },
            themeClasses() {
                return {
                    "theme-default": this.useDefaultTheme,
                    "holiday-us-traditional": this.useHolidayTheme,
                    "holiday-us-official": this.useHolidayTheme,
                }
            },
        },
        mounted() {
            this.newEventStartDate = this.isoYearMonthDay(this.today())
            this.newEventEndDate = this.isoYearMonthDay(this.today())
        },
        methods: {
            thisMonth(d, h, m) {
                const t = new Date()
                return new Date(t.getFullYear(), t.getMonth(), d, h || 0, m || 0)
            },
            onClickDay(d) {
                this.message = `You clicked: ${d.toLocaleDateString()}`
            },
            onClickEvent(e) {
                this.$set(this, 'selectedEvent', e.originalEvent);
                //this.selectedEvent = e.originalEvent;
            },
            setShowDate(d) {
                this.message = `Changing calendar view to ${d.toLocaleDateString()}`
                this.showDate = d
            },
            deleteSelected() {
                let self = this;
                axios.delete('/events/' + this.selectedEvent.id).then(r => {
                    let index = self.events.findIndex(e => e.id === self.selectedEvent.id);
                    self.selectedEvent = null;
                    self.events.splice(index, 1);
                });
            },
            addEvent() {
                let self = this;
                axios.post('/events', {
                    start_date: this.newEventStartDate,
                    end_date: this.newEventEndDate,
                    title: this.newEventTitle,
                    description: this.newEventDescription,
                    theme: this.newEventTheme,
                }).then(r => {
                    self.newEventTitle = "";
                    self.newEventDescription = "";
                    self.newEventStartDate = "";
                    self.newEventEndDate = "";
                    self.newEventTheme = "";
                    self.events.push({
                        id: r.data.id,
                        startDate: r.data.start_date,
                        endDate: r.data.end_date,
                        title: r.data.title,
                        description: r.data.description,
                        classes: r.data.theme,
                    });
                }).catch(e => {
                    alert('Error!');
                });
            },
        },
    }
</script>

<style scoped>
    html,
    body {
        height: 100%;
        margin: 0;
        background-color: #f7fcff;
    }

    .row {
        display: flex;
        flex-direction: row;
        font-family: Calibri, sans-serif;
        width: 95vw;
        min-width: 30rem;
        max-width: 100rem;
        min-height: 40rem;
        margin-left: auto;
        margin-right: auto;
    }

    .calendar-controls {
        margin-right: 1rem;
        min-width: 14rem;
        max-width: 14rem;
    }

    .calendar-parent {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        overflow-x: hidden;
        overflow-y: hidden;
        max-height: 80vh;
        background-color: white;
    }

    /* For long calendars, ensure each week gets sufficient height. The body of the calendar will scroll if needed */
    .cv-wrapper.period-month.periodCount-2 .cv-week,
    .cv-wrapper.period-month.periodCount-3 .cv-week,
    .cv-wrapper.period-year .cv-week {
        min-height: 6rem;
    }

    /* These styles are optional, to illustrate the flexbility of styling the calendar purely with CSS. */
    /* Add some styling for events tagged with the "birthday" class */
    .calendar .event.birthday {
        background-color: #e0f0e0;
        border-color: #d7e7d7;
    }

    .calendar .event.birthday::before {
        content: "\1F382";
        margin-right: 0.5em;
    }
</style>