import { createPlanet } from '@/services/api'
import { useRoverHelpers } from '../useRoverHelpers'

export function startCommand(state) {
  const { handleApiError } = useRoverHelpers()

  return {
    name: 'start',
    description: "Scan the planet's surface to find all obstacles and ensure the rover's launch",
    func: async ({ print }) => {
      try {
        const res = await createPlanet()
        state.planetId = res.data.id
        print(`Planetary coordinates mapped. Planet size: ${res.data.width} x ${res.data.height}`)
        print(`Deploy rovers using 'launch &lt;x&gt; &lt;y&gt; &lt;orientation (N, S, W, E)&gt;'`)
        print(`Example: launch 2 3 N`)
      } catch (err) {
        handleApiError(print, err, 'Unable to initialize planetary scan')
      }
    },
  }
}
