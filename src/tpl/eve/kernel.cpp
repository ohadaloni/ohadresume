/**
  * Arduino kernel
  */
/*------------------------------------------------------------*/
#include "Arduino.h"
/*------------------------------------------------------------*/
#include "thread.h"
#include "Kernel.h"
/*------------------------------------------------------------*/
Kernel::Kernel() {
	currentThread = -1;
	for(int i=0;i<MAX_THREADS;i++)
		threads[i] = NULL;
	now = 0;
}
/*------------------------------------------------------------*/
/*
 ** add a thread to the kernel Queue
 */
int Kernel::addThread(vFctPtr threadLoop) {
	int i;
	for(i=0;i<MAX_THREADS;i++)
		if ( threads[i] == NULL )
			break;
	if ( i == MAX_THREADS ) {
		Serial.println("MAX_THREADS exceeded");
		return(-1);
	}
	/*	printi("Adding Thread", i);	*/
	threads[i] = new Thread(threadLoop);
	return(i);
}
/*------------------------------------------------------------*/
void Kernel::kill(int pid) {
	threads[pid] = NULL;
	if ( currentThread == pid )
		currentThread = -1;
}
/*------------------------------*/
void Kernel::exit() {
	kill(currentThread);
}
/*------------------------------------------------------------*/
void Kernel::msleep(int milliSeconds) {
	if ( currentThread >= 0 )
		threads[currentThread]->sleepUntil = millis() + milliSeconds;
	else
		delay((unsigned long)milliSeconds);
}
/*------------------------------*/
void Kernel::sleep(int seconds) {
	msleep(seconds*1000);
}
/*------------------------------------------------------------*/
/**
 * show the kernel's initialize LED show sequence
 */
/*------------------------------*/
void Kernel::sig() {
	pinMode(9, OUTPUT);
	pinMode(10, OUTPUT);
	pinMode(11, OUTPUT);
	pinMode(12, OUTPUT);
	digitalWrite(9, HIGH);
	digitalWrite(10, HIGH);
	digitalWrite(11, HIGH);
	digitalWrite(12, HIGH);
	blink(9, 1, 50);
	blink(10, 2, 50);
	blink(11, 3, 50);
	blink(12, 4, 50);
}
/*------------------------------*/
void Kernel::blink(int pin, int times, int delayTime) {
	for(int i=0;i<times;i++) {
		digitalWrite(pin, LOW);
		delay(delayTime);
		digitalWrite(pin, HIGH);
		delay(delayTime);
	}
}
/*------------------------------------------------------------*/
void Kernel::setup() {
	Serial.begin(9600);
	sig();
}
/*------------------------------------------------------------*/
/**
 * call this from loop as the last (and usually only) step
 * when all processes are sleeping the arduino spends its time here
 * looping until some time has passed
 */
void Kernel::loop() {
	boolean idle = true;
	for(int i=0;i<MAX_THREADS;i++) {
		if ( threads[i] == NULL )
			continue;
		now = millis();
		if ( now < threads[i]->sleepUntil )
			continue;
		threads[i]->sleepUntil = 0;
		currentThread = i;
		threads[i]->loop();
		idle = false;
	}
	// when all threads are sleeping
	if ( idle )
		delay(1);
}
/*------------------------------------------------------------*/
