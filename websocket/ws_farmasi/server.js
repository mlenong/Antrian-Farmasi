const express = require('express');
const http = require('http');
const { Server } = require('socket.io');

const app = express();
const server = http.createServer(app);
const io = new Server(server, {
    cors: {
        origin: '*',
    }
});

io.on('connection', (socket) => {
    console.log('Client connected:', socket.id);

    socket.on('disconnect', () => {
        console.log('Client disconnected:', socket.id);
    });
});
//ws bpjs
app.post('/call', express.urlencoded({ extended: true }), (req, res) => {
    const { id, nama, antrian, sts_racikan} = req.body;
    io.emit('call', { id, nama, antrian, sts_racikan });
    res.status(200).send('Antrian dipanggil');
});

app.post('/done', express.urlencoded({ extended: true }), (req, res) => {
    const { id } = req.body;
    io.emit('done', { id });
    res.status(200).send('Antrian selesai');
});

app.post('/process', express.urlencoded({ extended: true }), (req, res) => {
    const { id, nama, antrian } = req.body;
    io.emit('process', { id, nama, antrian });
    res.send('Antrian diproses');
});

//ws eksekutif
app.post('/call-ekse', express.urlencoded({ extended: true }), (req, res) => {
    const { id, nama, antrian, sts_racikan} = req.body;
    io.emit('call-ekse', { id, nama, antrian, sts_racikan });
    res.status(200).send('Antrian dipanggil');
});

app.post('/done-ekse', express.urlencoded({ extended: true }), (req, res) => {
    const { id } = req.body;
    io.emit('done-ekse', { id });
    res.status(200).send('Antrian selesai');
});

app.post('/process-ekse', express.urlencoded({ extended: true }), (req, res) => {
    const { id, nama, antrian } = req.body;
    io.emit('process-ekse', { id, nama, antrian });
    res.send('Antrian diproses');
});


server.listen(3000, () => {
    console.log('WebSocket server running on port 3000');
});