import { executeCommands } from '@/services/api'
import { useRoverHelpers } from '../useRoverHelpers'

export function moveCommand(state) {
  const { directionToWord, printValidationErrors, handleApiError } = useRoverHelpers()

  return {
    name: 'move',
    description: 'Execute movement commands for the rover (F, L, R)',
    argDescriptions: ['movement commands (F=Forward, L=Left, R=Right)'],
    func: async ({ print }, commands) => {
      if (!state.roverId) {
        print("No rover deployed. Use 'launch' first.")
        return
      }

      if (!commands) {
        print('Usage: move &lt;commands&gt; (e.g., move FFRL)')
        return
      }

      try {
        const res = await executeCommands(state.roverId, commands.toUpperCase())

        if (res.data.path?.length) {
          print('Rover movement log:')
          res.data.path.forEach((step) => {
            const { x, y } = step.position
            const dirWord = directionToWord(step.direction)
            print(`- Moved ${step.movement} to (${x},${y}) facing ${dirWord}`)
          })
        }

        const finalPos = res.data.position
        const finalDir = directionToWord(res.data.direction)
        print(`\nFinal rover position: (${finalPos.x}, ${finalPos.y}) facing ${finalDir}`)
      } catch (err) {
        if (err.error === 1002 && err.path) {
          print('Obstacle detected! Rover halted.')
          if (err.path.length) {
            print('Partial movement path until obstacle:')
            err.path.forEach((step) => {
              const { x, y } = step.position
              const dirWord = directionToWord(step.direction)
              print(`- Moved ${step.movement} to (${x},${y}) facing ${dirWord}`)
            })
          }
          const { x, y } = err.coordinates
          print(`<br>Obstacle coordinates: (${x}, ${y})`)
          if (err.path.length) {
            const lastStep = err.path[err.path.length - 1]
            const lastX = lastStep.position.x
            const lastY = lastStep.position.y
            const lastDir = directionToWord(lastStep.direction)
            print(`Current rover position: (${lastX}, ${lastY}) facing ${lastDir}`)
          }
        } else if (err.error === 1000) {
          printValidationErrors(print, err)
        } else {
          handleApiError(print, err, 'Movement command failed')
        }
      }
    },
  }
}
