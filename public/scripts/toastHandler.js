const toastTrigger = document.getElementById('successToast')
const toastLiveExample = document.getElementById('liveToast')

if (toastTrigger) {
  const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
  toastTrigger.addEventListener('click', () => {
    console.log('click');
    toastBootstrap.show()
  })
}