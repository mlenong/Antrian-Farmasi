const socket = io('http://172.16.15.25:3000');

        const callList = document.getElementById('queue-call-list');
        const processList = document.getElementById('queue-process-list');
        const nonRacikanList = document.getElementById('queue-nonracikan-list');
        const processItemsContainer = document.getElementById('queue-process-items');

        function berkedipElemen(elemen) {
            elemen.classList.add('flash');
            setTimeout(() => {
                elemen.classList.remove('flash');
            }, 4000);
        }

        function playAudioSequence(audioFiles) {
            let index = 0;

            function playNext() {
                if (index < audioFiles.length) {
                    const audio = new Audio(audioFiles[index]);
                    audio.play();
                    audio.onended = playNext;
                    index++;
                }
            }

            playNext();
        }

        socket.on('call', (data) => {
            const queueItem = document.createElement('div');
            queueItem.classList.add('queue-item');
            queueItem.textContent = data.antrian;

            if (data.sts_racikan === 'Y') {
                callList.innerHTML = '';
                callList.appendChild(queueItem);
                berkedipElemen(queueItem);
            } else if (data.sts_racikan === 'T') {
                nonRacikanList.innerHTML = '';
                nonRacikanList.appendChild(queueItem);
                berkedipElemen(queueItem);
            }

            const antrian = data.antrian.toString().split('');
            const audioFiles = ['audio/bell.mp3', 'audio/antrian.mp3', ...antrian.map(num => `audio/${num}.mp3`), 'audio/loket14.mp3'];

            playAudioSequence(audioFiles);
        });

        socket.on('process', (data) => {
            const processItem = document.createElement('div');
            processItem.classList.add('queue-item');
            processItem.setAttribute('data-id', data.id);
            processItem.textContent = data.antrian;

            processItemsContainer.appendChild(processItem);

            if (processItemsContainer.children.length > 0) {
                processItemsContainer.style.animation = 'scrollDown 15s linear infinite';
            }
        });

        socket.on('done', ({ id }) => {
            const item = processItemsContainer.querySelector(`[data-id="${id}"]`);
            if (item) {
                processItemsContainer.removeChild(item);

                if (processItemsContainer.children.length === 0) {
                    processItemsContainer.innerHTML = '';
                }
            }
        });

        function doneProcess(id) {
            socket.emit('done', { id });
        }