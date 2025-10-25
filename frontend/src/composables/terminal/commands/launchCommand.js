import { launchRover } from '@/services/api'
import { useRoverHelpers } from '../useRoverHelpers'

export function launchCommand(state) {
  const { directionToWord, handleApiError } = useRoverHelpers()

  return {
    name: 'launch',
    description: 'Deploy a rover on the current planetary sector',
    argDescriptions: ['x coordinate', 'y coordinate', 'direction (N, S, E, W)'],
    func: async ({ print }, x, y, direction) => {
      if (!state.planetId) {
        print("No planetary sector detected. Run 'start' first.")
        return
      }
      if (!x || !y || !direction) {
        print('Usage: launch &lt;x&gt; &lt;y&gt; &lt;direction&gt; (e.g., launch 2 3 N)')
        return
      }

      try {
        const res = await launchRover(state.planetId, Number(x), Number(y), direction.toUpperCase())
        state.roverId = res.data.id

        const dirWord = directionToWord(direction)
        print(`Rover deployed successfully at coordinates (${x},${y}) facing ${dirWord}.`)
        print(
          `Command movement sequences using 'move &lt;commands&gt;' (F=forward, L=left, R=right)`,
        )
        print(`Example: move FFRL`)
      } catch (err) {
        handleApiError(print, err, 'Deployment failure')
      }
    },
  }
}
