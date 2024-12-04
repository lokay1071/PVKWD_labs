import { Module } from '@nestjs/common';
import { CategoriesController } from './categories.controller';
import { CategoriesService } from './categories.service';
import { TypeOrmModule } from '@nestjs/typeorm';
import { Category } from './category.entity';
import { ProductsService } from 'src/products/products.service'; // Import ProductsService
import { Product } from 'src/products/product.entity'; // Import Product entity

@Module({
  imports: [
    TypeOrmModule.forFeature([Category, Product]), // Make both Category and Product repositories available
  ],
  controllers: [CategoriesController],
  providers: [CategoriesService, ProductsService], // Add ProductsService to providers
  exports: [TypeOrmModule],
})
export class CategoriesModule {}
