import { getCommands } from './commands'

export function useTerminalCommands() {
  const state = {
    planetId: null,
    roverId: null,
  }

  const commands = getCommands(state)

  return { commands, state }
}
