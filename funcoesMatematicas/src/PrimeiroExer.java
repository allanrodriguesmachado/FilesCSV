	/**
	 * Fazer um programa para ler as medidas da largura e comprimento de um terreno retangular com uma casa 
	 * decimal, bem como o valor do metro quadrado do terreno com duas casas decimal, bem como o valor do metro
	 * quadrado do terreno com duas casas decimais. Em seguida, o programa deve mostrar o valor da área do terreno
	 * bem como o valor do preço do terreno, ambos com duas casas decimais
	 * 
	 *       +++ Entrada de dados +++              +++ Saída de dados +++  
	 * 		
	 * 		        10.0								Area = 300.00
	 * 		        30.0								Preco = 60000.00
	 * 		        200.00
	 * 
	 *  ====> Formula 
	 *  	área = largura x comprimento
	 *  	preço = área x preco do metro quadrado
	 *  
	 *   ======================================== RESPOSTA =====================================
	 */


import java.util.Locale;
import java.util.Scanner;

public class PrimeiroExer {
	
	public static void main(String[] args){
		
	Locale.setDefault(Locale.US);
	Scanner sc = new Scanner(System.in);
		
		double largura = sc.nextDouble();
		double comprimento = sc.nextDouble();
		double metroQuadrado = sc.nextDouble();
		
		double area = largura * comprimento;
		double preco = area * metroQuadrado;
		
		System.out.printf("Area = %.2f%n", area);
		System.out.printf("Preco = %.2f%n", preco);
		
		sc.close();
	}
}
