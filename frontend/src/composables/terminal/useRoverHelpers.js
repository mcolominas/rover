export function useRoverHelpers() {
  function directionToWord(dir) {
    const map = {
      N: 'NORTH',
      S: 'SOUTH',
      E: 'EAST',
      W: 'WEST',
    }
    return map[dir?.toUpperCase()] || dir
  }

  function printValidationErrors(print, err) {
    print('Failure detected:')
    const errors = err.errors || {}
    Object.keys(errors).forEach((field) => {
      errors[field].forEach((msg) => print(`- ${field}: ${msg}`))
    })
  }

  function handleApiError(print, err, defaultMsg) {
    if (err.error === 1000) {
      printValidationErrors(print, err)
    } else {
      print(`${defaultMsg}: ${err.message || JSON.stringify(err)}`)
    }
  }

  return { directionToWord, printValidationErrors, handleApiError }
}
