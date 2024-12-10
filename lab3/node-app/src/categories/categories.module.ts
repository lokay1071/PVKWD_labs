import { Module } from '@nestjs/common';
import { CategoriesController } from './categories.controller';
import { CategoriesService } from './categories.service';
import { TypeOrmModule } from '@nestjs/typeorm';
import { Category } from './category.entity';
import { ProductsService } from 'src/products/products.service';
import { Product } from 'src/products/product.entity';

@Module({
  imports: [TypeOrmModule.forFeature([Category, Product])],
  controllers: [CategoriesController],
  providers: [CategoriesService, ProductsService],
  exports: [TypeOrmModule],
})
export class CategoriesModule {}
