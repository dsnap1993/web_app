package main

import (
	"fmt"
	"log"
	"github.com/google/gopacket"
	"github.com/google/gopacket/pcap"
)

var (
	pcapfile string = "test.pcap"
	handle *pcap.Handle
	err error
)

func openFile() {
	handle, err := pcap.OpenOffline(pcapFile)
	if err != nil {
		log.Fatal(err)
	}
	defer handle.Close()

	packetSource := gopacket.NewPacketSource(handle, handle.LinkType())
	for packet := range packetSource.Packet() {
		fmt.Println(packet)
	}
}