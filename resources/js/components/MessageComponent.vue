<template>
    <div>
        <ul class="chat">
            <li class="left clearfix" v-for="message in messages">
                <div class="chat-body clearfix">
                    <p>
                        {{ message.message }}
                    </p>
                </div>
            </li>
        </ul>
        <div class="input-group">
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
            'to'
        ],
        data() {
            return {
                newMessage: ''
            }
        },
        mounted() {
            Echo.channel('chat')
                .listen('MessageSentEvent', (e) => {
                    this.messages.push({
                        message: e.message.message
                    });
                    console.log(e.message.message);
                    this.newMessage = '';
                });
        },
        methods: {
            sendMessage() {
                axios.post('/send', {
                    message: this.newMessage,
                    from: this.from,
                    to: this.to,
                })
                    .then(function (response) {
//                        this.messages.push(response.message);
                        console.log(response);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        },

    };
</script>