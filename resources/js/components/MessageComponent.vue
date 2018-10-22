<template>
    <div>
        <ul class="chat" v-chat-scroll="{always: false}">
            <li class="left clearfix p-3" v-for="message in msg" :class="className">
                <div class="chat-body clearfix">
                    <div class="header">
                        <strong class="primary-font">
                            {{ message.user }}
                        </strong>
                    </div>
                    <p>
                        {{ message.message }}
                    </p>
                </div>
                <!--<small class="badge float-right" :class="badgeClass">{{ }}</small>-->
            </li>
        </ul>
        <div class="input-group mt-3">
            <input id="btn-input" type="text" name="message" class="form-control input-sm" placeholder="Type your message here..." v-model="newMessage" @keyup.enter="sendMessage">
            <span class="input-group-btn">
                <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">
                    Send
                </button>
            </span>
        </div>
    </div>
</template>

<script>


    export default {
        props: [
            'messages',
            'from',
            'to',
            'color',
            'user'
        ],
        data() {
            return {
                newMessage: '',
                msg: this.messages,
//                users: this.user
                colors: []
            }
        },
        computed: {
            className(){
                return 'list-group-item-'+this.color;
            },
            badgeClass(){
                return 'badge-'+this.color;
            }
        },
        mounted() {
            Echo.private('chat')
                .listen('MessageSentEvent', (e) => {
                    console.log(e.message.to);
                        this.msg.push({
                            message: e.message.message,
                            user: e.user.name
                        });
                        console.log(this.user);
//                    this.users.push(e.user);
//                    this.color.push('danger');
                });
        },
        methods: {
            sendMessage() {
                const _this = this;
//                _this.users.push('you');
                axios.post('/send', {
                    message: this.newMessage,
                    from: this.from,
                    to: this.to,
                })
                    .then(function (response) {
                        _this.newMessage = '';
//                        _this.color.push('success');
//                        console.log(response);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        },

    };
</script>