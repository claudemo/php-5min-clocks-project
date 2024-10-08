<template>
    <div>
        <!-- Existing Components -->
        <timer-component></timer-component>

        <!-- New Prompt Area -->
        <div class="prompt-area">
            <h2>OpenAI Area</h2>
            <textarea v-model="userPrompt" placeholder="Enter your prompt"></textarea>
            <button @click="submitPrompt">Submit</button>
        </div>

        <!-- Display OpenAI Response -->
        <div class="api-response" v-if="apiResponse">
            <h3>The GPT Friend:</h3>
            <p>{{ apiResponse }}</p>
        </div>
    </div>
</template>

<script>
import TimerComponent from './components/TimerComponent.vue';
import axios from 'axios';

export default {
    name: 'App',
    components: {
        TimerComponent,
    },
    data() {
        return {
            userPrompt: '',
            apiResponse: '',
        };
    },
    methods: {
        async submitPrompt() {
            try {
                console.log('Submitting prompt:', this.userPrompt);
                const response = await axios.post('/openai', {
                    task: this.userPrompt,
                });
                this.apiResponse = response.data.reply;
            } catch (error) {
                console.error('Error calling OpenAI API:', error);
                // Add error response logging
                if (error.response) {
                    console.error('Server responded with:', error.response.data);
                }
            }
        },
    },
};
</script>

<style scoped>
/* Add styles for your prompt area and response here */
.prompt-area {
    margin-top: 20px;
}

.prompt-area textarea {
    width: 100%;
    height: 100px;
}

.prompt-area button {
    margin-top: 10px;
}

.api-response {
    width: 100%;
    margin-top: 20px;
    background-color: #f1f4f7;
    padding: 2px;
    border-radius: 5px;
    font-size: 1.2em;
    display: flex;
    /* 设置为flex容器 */
    flex-direction: column;
    /* 垂直排列 */

    /* 垂直居中内容 */
    align-items: center;

    /* 水平居中内容 */
    min-height: 100vh;
    /* 占满整个视窗高度 */
    box-sizing: border-box;
    /* 包括padding在内 */
    word-wrap: break-word;
    /* 自动换行 */
    white-space: normal;
    /* 允许换行 */
    overflow-wrap: break-word;
    /* 防止文字溢出容器 */
}

.api-response h3 {
    font-size: 1.5em;
    margin-top: 0;
    text-align: center;
}

.api-response p {
    margin-left: 20rem;
    margin-right: 10rem;
    line-height: 1.6;
    text-align: center;
    word-wrap: break-word;
    /* 自动换行 */
    white-space: normal;
    /* 允许换行 */
    overflow-wrap: break-word;
    /* 防止文字溢出 */
}

.prompt-area {
    margin-top: 0px;
    display: flex;
    justify-content: center;
    /* Center the prompt area */
    align-items: center;
    flex-direction: column;
}

.prompt-area textarea {
    width: 50%;
    height: 50px;
    /* Increased height for better usability */
    padding: 10px;
    /* Add padding for better appearance */
    font-size: 1em;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}


.prompt-area {
    margin-top: 0px;

}

.prompt-area textarea {
    width: 50%;
    height: 20px;
    /* Increase height if desired */
    padding: 0px;
    font-size: 1em;
    border-radius: 5px;
    border: 1px solid #ccc;
}

body,
html {
    margin: 0;
    padding: 0;
    height: 100%;
    display: flex;
    justify-content: center;
    /* Center horizontally */
    align-items: center;
    /* Center vertically */
    background-color: #f8f9fa;
    /* Optional background color */
}
</style>