document.addEventListener("DOMContentLoaded", () => {
    console.log("DOM fully loaded and parsed");
    
    const steps = document.querySelectorAll(".form-step");
    const nextBtns = document.querySelectorAll(".next-btn");
    const prevBtns = document.querySelectorAll(".prev-btn");
    const stepIndicators = document.querySelectorAll(".step");

    let currentStep = 0;

    function updateSteps() {
        console.log("Updating steps:", currentStep);
        
        steps.forEach((step, index) => {
            step.classList.toggle("active", index === currentStep);
        });

        stepIndicators.forEach((indicator, index) => {
            indicator.classList.toggle("active", index === currentStep);
        });
    }

    // Handle "Next" buttons
    nextBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            if (currentStep < steps.length - 1) {
                currentStep++;
                updateSteps();
            }
        });
    });

    // Handle "Previous" buttons
    prevBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            if (currentStep > 0) {
                currentStep--;
                updateSteps();
            }
        });
    });

    updateSteps(); // Initialize the first step

    // Summary section for Step 3
    document.querySelector(".confirm-btn").addEventListener("click", (e) => {
        e.preventDefault();
        document.getElementById('summary-service').textContent = document.getElementById('service-select').value;
        document.getElementById('summary-therapist').textContent = document.getElementById('therapist-select').value;
        document.getElementById('summary-date').textContent = document.getElementById('date-picker').value;
        document.getElementById('summary-time').textContent = document.querySelector('.time-slots .time-slot.active').textContent;
        document.getElementById('summary-price').textContent = '$' + (document.getElementById('service-select').value === 'massage' ? '50' : document.getElementById('service-select').value === 'facial' ? '40' : '30');
    });

    // Highlight selected time slot
    document.querySelectorAll(".time-slot").forEach(slot => {
        slot.addEventListener("click", () => {
            document.querySelectorAll(".time-slot").forEach(slot => slot.classList.remove("active"));
            slot.classList.add("active");
        });
    });
});

// FOR BOOKING

document.addEventListener("DOMContentLoaded", () => {
    const steps = document.querySelectorAll(".form-step");
    const nextButtons = document.querySelectorAll(".next-btn");
    const prevButtons = document.querySelectorAll(".prev-btn");
    const timeSlotsContainer = document.querySelector(".time-slots");
    const serviceSelect = document.getElementById("service-select");
    const therapistSelect = document.getElementById("therapist-select");
    const datePicker = document.getElementById("date-picker");
    const startTimeInput = document.getElementById("start-time");

    let currentStep = 0;

    // Show current step
    function showStep(index) {
        steps.forEach((step, i) => {
            step.classList.toggle("active", i === index);
        });
    }

    // Generate time slots
    function generateTimeSlots() {
        const therapistId = therapistSelect.value;
        const serviceDuration = +serviceSelect.selectedOptions[0]?.dataset?.duration || 0;

        if (!therapistId || !datePicker.value) {
            timeSlotsContainer.innerHTML = "<p>Please select a therapist and date.</p>";
            return;
        }

        const availability = availabilityData[therapistId];
        if (!availability) {
            timeSlotsContainer.innerHTML = "<p>No availability for the selected therapist.</p>";
            return;
        }

        const startTime = new Date(`${datePicker.value}T${availability.start_time}`);
        const endTime = new Date(`${datePicker.value}T${availability.end_time}`);
        const slots = [];

        while (startTime < endTime) {
            const slotStart = startTime.toTimeString().slice(0, 5);
            startTime.setMinutes(startTime.getMinutes() + serviceDuration);
            const slotEnd = startTime.toTimeString().slice(0, 5);

            if (startTime <= endTime) {
                slots.push(`<button type="button" class="time-slot" data-start="${slotStart}">${slotStart} - ${slotEnd}</button>`);
            }
        }

        timeSlotsContainer.innerHTML = slots.length
            ? slots.join("")
            : "<p>No available time slots for the selected date.</p>";

        document.querySelectorAll(".time-slot").forEach(slot => {
            slot.addEventListener("click", () => {
                document.querySelectorAll(".time-slot").forEach(s => s.classList.remove("selected"));
                slot.classList.add("selected");
                startTimeInput.value = slot.dataset.start;
            });
        });
    }

    // Validate current step
    function validateStep(index) {
        const inputs = steps[index].querySelectorAll("[required]");
        for (const input of inputs) {
            if (!input.value) {
                alert("Please complete all required fields.");
                return false;
            }
        }
        return true;
    }

    // Display summary in Step 3
    function updateSummary() {
        document.getElementById("summary-service").textContent = serviceSelect.selectedOptions[0]?.text || "";
        document.getElementById("summary-therapist").textContent = therapistSelect.selectedOptions[0]?.text || "";
        document.getElementById("summary-date").textContent = datePicker.value;
        document.getElementById("summary-time").textContent = startTimeInput.value;
        document.getElementById("summary-price").textContent = serviceSelect.selectedOptions[0]?.dataset?.price || "";
    }

    // Event listeners for navigation
    nextButtons.forEach((btn, index) => {
        btn.addEventListener("click", () => {
            if (validateStep(index)) {
                currentStep++;
                showStep(currentStep);

                if (currentStep === 2) {
                    updateSummary();
                }
            }
        });
    });

    prevButtons.forEach((btn, index) => {
        btn.addEventListener("click", () => {
            currentStep--;
            showStep(currentStep);
        });
    });

    // Generate time slots when therapist, date, or service changes
    therapistSelect.addEventListener("change", generateTimeSlots);
    datePicker.addEventListener("change", generateTimeSlots);
});



