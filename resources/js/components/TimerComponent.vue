<template>
  <div class="container">
  <div class="task-bar">
      <h2>Remember your goal for this 5 min countdown!</h2>
      <input v-model="task" placeholder="Enter your task" />
    </div>
 
    <div class="timer-display">
      <span class="time">{{ formattedTime }}</span>
    </div>
    <div class="button-group">
      <button @click="startTimer" :disabled="timerActive">Start Timer</button>
      <button @click="resetTimer" :disabled="!timerActive">Reset Timer</button>
    </div>
    <div class="api-response" v-if="apiResponse">
      <h3>OpenAI GPT Response:</h3>
      <p>{{ apiResponse }}</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TimerComponent',
  data() {
    return {
      totalTime: 300, // Total time in seconds (5 minutes)
      timeLeft: 300,
      timer: null,
      intervalCount: 0,
      task: '',
      timerActive: false,
      apiResponse: '',
    };
  },
  computed: {
    formattedTime() {
      const minutes = Math.floor(this.timeLeft / 60);
      const seconds = this.timeLeft % 60;
      return `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
    },
  },
  methods: {
    startTimer() {
      if (this.timer) return;
      this.timerActive = true;
      this.timer = setInterval(() => {
        if (this.timeLeft > 0) {
          this.timeLeft--;
        } else {
          this.intervalCount++;
          this.resetTimer(true);
          this.callOpenAIApi();
        }
      }, 1000);
    },
    resetTimer(autoRestart = false) {
      clearInterval(this.timer);
      this.timer = null;
      this.timeLeft = this.totalTime;
      this.timerActive = false;
      if (autoRestart) {
        this.startTimer();
      }
    },
    async callOpenAIApi() {
      try {
        const response = await axios.post('/api/openai', {
          task: this.task,
          intervals: this.intervalCount,
        });
        this.apiResponse = response.data.reply;
      } catch (error) {
        console.error('Error calling OpenAI API:', error);
      }
    },
  },
};
</script>

<style scoped>
/* Container Styles */
.container {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  text-align: center;
}

/* Title Styles */
.title {
  font-size: 2.5em;
  margin-bottom: 20px;
}

/* Timer Display Styles */
.timer-display {
  background-color: #f0f0f0;
  border-radius: 10px;
  padding: 5px;
  margin-bottom: 20px;
}

.time {
  font-size: 2em;
  font-weight: bold;
  color: #333;
}

/* Button Group Styles */
.button-group {
  margin-bottom: 20px;
}

.button-group button {
  background-color: #ADD8E6;
  border: none;
  color: white;
  padding: 15px 25px;
  margin: 5px;
  font-size: 1em;
  cursor: pointer;
  border-radius: 5px;
}

.button-group button:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}

.button-group button:hover:not(:disabled) {
  background-color: black;
}

/* Task Bar Styles */
.task-bar {
  margin-bottom: 20px;
}

.task-bar input {
  width: 80%;
  padding: 10px;
  font-size: 1em;
  border: 1px solid #ccc;
  border-radius: 5px;
}

/* Interval Count Styles */
.interval-count {
  font-size: 1.2em;
  margin-bottom: 20px;
}

/* API Response Styles */
.api-response {
  background-color: #e9ecef;
  padding: 15px;
  border-radius: 5px;
  text-align: left;
}

.api-response h3 {
  margin-top: 0;
}

.api-response p {
  margin-bottom: 0;
}

/* Responsive Design */
@media (max-width: 600px) {
  .container {
    padding: 10px;
  }

  .time {
    font-size: 3em;
  }

  .button-group button {
    width: 100%;
    margin: 5px 0;
  }

  .task-bar input {
    width: 100%;
  }
}
</style>