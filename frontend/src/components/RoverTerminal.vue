<script setup>
import { onMounted } from 'vue'
import { initTerminal } from 'ttty'
import { useTerminalCommands } from '@/composables/terminal/useTerminalCommands'

onMounted(() => {
  const el = document.getElementById('terminal')
  if (!el) return

  const { commands } = useTerminalCommands()

  initTerminal({
    host: el,
    prompt: 'mars@rover:~$ ',
    welcomeMessage:
      "Rover Control Terminal Online.<br>Type 'start' to initiate planetary scan or type 'help' to see all instructions.<br><br>",
    commands,
  })
})
</script>

<template>
  <div class="terminal-overflow">
    <div id="terminal" class="terminal"></div>
  </div>
</template>

<style>
.terminal-overflow {
  overflow-y: auto;
  flex: 1;
}

.terminal {
  height: 100%;
}
</style>
