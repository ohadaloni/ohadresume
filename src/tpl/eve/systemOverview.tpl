An EKG device is attached to a patient.
It transmits the data via bluetooth to a local app
on a nearby phone.

The phone trasmits the data to servers in the cloud.

On the visualization side, the doctor's phone takes the data from the web server.

It then sends it to the visualization device, via wifi.

The visualazation device is an arduino device connected to a LED strip.

The twelve leads of the EKG data are presented as twelve tricolor LEDs,
where the blue represents postive numbers, the red negative,
and the intensity of light represents the value.

The micro kernel is designed to enable the paralellism of processes on the device.
there are thirteen processes in all:
	one is the web server receving the data.
	When it receives a visualization request,
	it starts 12 processes, each representing one of the leads - one EKG graph.

