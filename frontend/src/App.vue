<script setup>
import RoverTerminal from './components/RoverTerminal.vue'
</script>

<template>
  <section class="terminal-section">
    <h1 class="title">Mars Rover Mission</h1>

    <RoverTerminal />
  </section>
</template>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.terminal-section {
  display: flex;
  flex-direction: column;
  height: 100dvh;
  background-color: black;
  color: white;
}

.title {
  text-align: center;
  margin-top: 1rem;
  margin-bottom: 0.5rem;
}
</style>
