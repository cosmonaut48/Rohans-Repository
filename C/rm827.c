/*
* Project Assessment for the PHY2027 module
* Date: 03/12/2022
*/
/*
 *
 * Program description: This program takes a set of inputs for polynomials of any order and allows the user to choose from 3
 *                      iterative integration methods. I used the thee methods outlined in the link below
 *
 *
 *
 https://math.libretexts.org/Courses/Mount_Royal_University/MATH_2200%3A_Calculus_for_Scientists_II/2%3A_Techniques_of_Integration/2.5%3A_Numerical_Integration_-_Midpoint%2C_Trapezoid%2C_Simpson's_rule#:~:text=The%20most%20commonly%20used%20techniques,definite%20integral%20using%20trapezoidal%20approximations.
 */


#include <stdio.h>
#include <stdlib.h>
#include <stdbool.h>
#include <unistd.h>
#include <math.h>

#define NMAX 30

typedef struct numerical {  //struct to pass the parameters of the integrands to each function
int order;
int divisions;
float fmin;
float fmax;
} Numerical;

void clear_buffer(void);
int intvalid(void);
float floatvalid(void);
char charvalid(void);
float polynomial(float coefficients[], int order, float x);

float traprule(Numerical trapz, float coefficients[]);
float midrule(Numerical mid, float coefficients[]);
float simprule(Numerical mid, float coefficients[]);



int main(){
    char menuInput[NMAX];
    int integralChoice;

    printf("What is the order of the polynomial you would like to integrate?\nPlease enter a non-zero value!\n");
    int order = intvalid();
    float coefficients[order+1];
    printf("And what is the coefficient for each term in this expansion, starting from x^%d\n",order);
    printf("Please note, any non-numeric input will be discarded and viewed as 0 or a null character\n");
    for(int i = 0;i<order+1;++i){
        printf("Coefficient for x^%d: ",order-i);
        scanf("%[^\n]c",&menuInput);
        coefficients[i] = atof(menuInput); //converts input to a float character
        clear_buffer(); //this is called multiple times in my code so will define here and in function - this clears the input buffer/stdin so that it doesnt interrupt future scanf
    }
    int repeater=1;
    do{
        printf("Now please select your choice of integration:\n\t(1) - Trapezoidal Method\n\t(2) - Midpoint Method\n\t(3) - Simpson's Method\n");
        do{
            integralChoice=intvalid(); //validates an integer input
            if(integralChoice != 1 && integralChoice != 2 && integralChoice != 3){
                printf("Try again\n"); //validates the integer restrictions
            }

        }while(integralChoice != 1 && integralChoice != 2 && integralChoice != 3);
        printf("Please enter the lower bound of your integral: ");
        float fmin = floatvalid();
        printf("And the upper bound? ");
        float fmax = floatvalid();
        printf("Finally, how many divisions are you looking to iterate over? ");
        int divisions = intvalid();
        Numerical iterativeData = {order,divisions,fmin,fmax};
        switch(integralChoice){
        case 1:
            printf("Completing the trapezium approximation..\n");
            printf("Your estimated area is %f\n",traprule(iterativeData, coefficients));
            break;
        case 2:
            printf("Completing the midpoint approximation..\n");
            printf("Your estimated area is %f\n",midrule(iterativeData, coefficients));
            break;
        case 3:
            printf("Completing the Simpson's approximation..\n");
            printf("Your estimated area is %f\n",simprule(iterativeData, coefficients));
            break;
        default:
            printf("it appears an error has occurred here - we will try that again!");

        }
        printf("Would you like to go again? Say 0 if no\n");
        scanf("%d",&repeater);
        clear_buffer();
    }while(repeater!=0);
    printf("Goodbye!");
return 0;
}


void clear_buffer(void){ //this clears the input buffer to prevent interruptions of future scanf commands, especially when an input error occurs
    while(true){
        int c = getc(stdin);
        if (c==EOF || c== '\n'){
            break;
        }
    }
}

int intvalid(void){ //this validates an integer input - any incorrect inputs are passed as 0, otherwise the int is passed
    int valid = 0;
    char menuInput[NMAX];
    do{
        scanf("%[^\n]c",&menuInput);
        if(atoi(menuInput)== 0){
            clear_buffer();
            return 0;
        }else{
            clear_buffer();
            return atoi(menuInput);
        }
    }while(valid!=1);
}

float floatvalid(void){ //this validates a float input. any incorrect inputs are passed as 0. all correct inputs are passed successfully
    int valid = 0;
    char menuInput[NMAX];
    do{
        scanf("%[^\n]c",&menuInput);
        if(atof(menuInput)== 0){
            clear_buffer();
            return 0;
        }else{
            clear_buffer();
            return atof(menuInput);
        }
    }while(valid!=1);
}

float polynomial(float coefficients[], int order, float x){ //this operates a polynomial function through a value of x to obtain a static result
    float result=0;
    for(int j=0;j<order+1;++j){
        result+=coefficients[j]*pow(x,order-j);
        //printf("P:%f ",result);
    }
return result;
}

float traprule(Numerical trapz, float coefficients[]){ //this operates the trapezium rule on a given range of values for a given polynomial
    float result=0;
    int order = trapz.order;
    float h = (trapz.fmax-trapz.fmin)/trapz.divisions;
    for (float i=trapz.fmin+h;i<trapz.fmax;i+=h){
        result+=polynomial(coefficients,order,i)*2;
    }
    result+=polynomial(coefficients,order,trapz.fmin);
    result+=polynomial(coefficients,order,trapz.fmax);
    result=result*(h/2);
return result;
}

float midrule(Numerical mid, float coefficients[]){ //this operates the midpoint rule on a given range of values for a given polynomial
    float result=0;
    int order = mid.order;
    float h = (mid.fmax-mid.fmin)/mid.divisions;
    for(float i =(mid.fmin+h)/2;i<mid.fmax;i+=h){
        result+=h*polynomial(coefficients,order,i);
    }
return result;
}

float simprule(Numerical simp, float coefficients[]){ //this operates the simpsons rule on a given range of values for a given polynomial
    float result=0;
    int numerate = 0;
    int order = simp.order;
    float h = (simp.fmax-simp.fmin)/simp.divisions;
    for(float i=simp.fmin+h;i<simp.fmax;i+=h){
        if(numerate%2==0){
            result+=4*polynomial(coefficients,order,i);
        }else if(numerate%2!=0){
            result+=2*polynomial(coefficients,order,i);
        }
        numerate+=1;
    }
    result+=polynomial(coefficients,order,simp.fmin);
    result+=polynomial(coefficients,order,simp.fmax);
    result=result*(h/3);
return result;
}




/*
RESULTS FOR MY CODE

1) x4+2x3+3x2+4x+5

What is the order of the polynomial you would like to integrate?
Please enter a non-zero value!
4
And what is the coefficient for each term in this expansion, starting from x^4
Please note, any non-numeric input will be discarded and viewed as 0 or a null character
Coefficient for x^4: 1
Coefficient for x^3: 2
Coefficient for x^2: 3
Coefficient for x^1: 4
Coefficient for x^0: 5
Now please select your choice of integration:
        (1) - Trapezoidal Method
        (2) - Midpoint Method
        (3) - Simpson's Method
1
Please enter the lower bound of your integral: 0
And the upper bound? 10
Finally, how many divisions are you looking to iterate over? 100
Completing the trapezium approximation..
Your estimated area is 26253.857422
Would you like to go again? Say 0 if no
1
Now please select your choice of integration:
        (1) - Trapezoidal Method
        (2) - Midpoint Method
        (3) - Simpson's Method
2
Please enter the lower bound of your integral: 0
And the upper bound? 10
Finally, how many divisions are you looking to iterate over? 100
Completing the midpoint approximation..
Your estimated area is 26248.029297
Would you like to go again? Say 0 if no
1
Now please select your choice of integration:
        (1) - Trapezoidal Method
        (2) - Midpoint Method
        (3) - Simpson's Method
3
Please enter the lower bound of your integral: 0
And the upper bound? 10
Finally, how many divisions are you looking to iterate over? 100
Completing the Simpson's approximation..
Your estimated area is 26249.972656
Would you like to go again? Say 0 if no
0
Goodbye!
Process returned 0 (0x0)   execution time : 21.031 s
Press any key to continue.

IN THIS CASE:
TRAPEZOID GIVES     26253.857422
MIDPOINT GIVES      26248.029297
SIMPSON'S GIVES     26249.972656
Over a [0,10] range and 100 intervals
The true result is 26250, making the Simpson's rule give the most accurate result

FOR FUTURE CASES I WILL ONLY GIVE THE OUTPUTS TO SAVE READING TIME

2a) x^3 + 2x

IN THIS CASE:
TRAPEZOID GIVES     2750.541504
MIDPOINT GIVES      2599.730469
SIMPSON'S GIVES     2700.000732
Over a [0,10] range and 68 intervals
The true result is 2600 making the Midpoint rule give the most accurate result

2b) x^3 + 2x

IN THIS CASE:
TRAPEZOID GIVES     2600.247559
MIDPOINT GIVES      2599.872070
SIMPSON'S GIVES     2599.998047
Over a [0,10] range and 100 intervals
The true result is 2600 making the Simpson's rule give the most accurate result

2c) x^3 + 2x

IN THIS CASE:
TRAPEZOID GIVES     2625.000000
MIDPOINT GIVES      2587.500000
SIMPSON'S GIVES     2600.000000
Over a [0,10] range and 10 intervals
The true result is 2600 making the Simpson's rule give the most accurate result

3) 5x^5 + 3x^3 + x

IN THIS CASE:
TRAPEZOID GIVES     841091.187500
MIDPOINT GIVES      840777.625000
SIMPSON'S GIVES     840882.312500
Over a [0,10] range and 100 intervals
The true result is 84083.333.... making the Simpson's rule give the most accurate result
*/
