import { startCommand } from './startCommand'
import { launchCommand } from './launchCommand'
import { moveCommand } from './moveCommand'

export function getCommands(state) {
  return {
    start: startCommand(state),
    launch: launchCommand(state),
    move: moveCommand(state),
  }
}
